<?php

namespace Modules\Docs\Http\Controllers\Manage;

use App\Jobs\SendEmailQueueJob;
use Auth;
use Arr;
use Storage;
use PDF;
use Illuminate\Http\Request;
use Modules\Docs\Http\Controllers\Controller;
use Modules\Docs\Models\Document;
use Modules\Docs\Enums\DocumentTypeEnum;
use Modules\Docs\Http\Requests\Manage\StoreRequest;
use Modules\Docs\Http\Requests\Manage\UpdateRequest;
use Modules\Docs\Notifications\DirectNotification;

class DocumentController extends Controller
{
    /**
     * List all documents
     */
    public function index(Request $request)
    {
        $employee = Auth::user()->employee;
        $include = array_filter([DocumentTypeEnum::GUIDE, DocumentTypeEnum::NOTE, DocumentTypeEnum::COMPANY, DocumentTypeEnum::SPECIAL]);
        $exclude = array_filter([DocumentTypeEnum::SYSTEM, DocumentTypeEnum::TAX]);

        return view('docs::manage.index', [
            'types' => collect($include),
            'document_count' => Document::whereNotIn('type', $exclude)->count(),
            'documents' => Document::with('modelable')->whereNotIn('type', $exclude)
                ->when($request->get('search'), fn ($lb) => $lb->where('label', 'like', '%' . $request->get('search') . '%'))
                ->when($request->get('type'), fn ($t) => $t->whereType($request->get('type')))
                ->when($request->get('trashed'), fn ($q) => $q->onlyTrashed())
                ->orderByDesc('id')
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * create new document
     */
    public function create()
    {
        $param = array_filter([DocumentTypeEnum::GUIDE, DocumentTypeEnum::NOTE, DocumentTypeEnum::COMPANY, DocumentTypeEnum::SPECIAL, DocumentTypeEnum::IT]);

        return view('docs::manage.create', [
            'types' => collect($param),
        ]);
    }

    /**
     * store new document
     */
    public function store(StoreRequest $request)
    {
        $user = $request->user();
        $doc  = $request->transformed()->toArray();
        $employees = DocumentTypeEnum::tryFrom($doc['type'])->docRecipient();

        $document = new Document(Arr::except($doc, ['file']));
        if ($document->save()) {
            // check if input has document
            if (!$doc['file']) {
                // create signature
                $document->signatures()->create(['qr' => $document->qr, 'user_id' => $user->id]);
                // signature
                $signatures = [
                    [
                        'position' => $user->employee->position->position->name ?? '',
                        'qr'       => $document->signatures->firstWhere('user_id', $user->id)?->qr,
                        'name'     => $user->name,
                    ],
                ];
                $text = $doc['meta']['content'];
                // create new file
                Storage::disk('docs')->put($document['path'], PDF::loadView('docs::manage.report', compact('doc', 'signatures', 'text'))->output());
            }

            foreach ($employees as $index => $employee) {
                $employee->user->notify(new DirectNotification($document, $index * 5));
            }
            return redirect()->next()->with('success', 'Dokumen dengan nama <strong>' . $document->label . '</strong> berhasil diunggah ke sistem, terima kasih.');
        }
        return redirect()->fail();
    }

    /**
     * preview document
     */
    public function show(Document $document)
    {
        return view('docs::manage.show', [
            'doc' => $document
        ]);
    }

    /**
     * edit document
     */
    public function edit(Document $document)
    {
        $param = array_filter([DocumentTypeEnum::GUIDE, DocumentTypeEnum::NOTE, DocumentTypeEnum::COMPANY, DocumentTypeEnum::SPECIAL]);

        return view('docs::manage.edit', [
            'types' => collect($param),
            'doc' => $document
        ]);
    }

    /**
     * edit document
     */
    public function update(Document $document, UpdateRequest $request)
    {
        $user = $request->user();
        $doc  = $request->transformed()->toArray();

        $document->fill(Arr::except($doc, ['file']));
        if ($document->save()) {

            // check if document has file or not
            if (!$doc['file']) {

                // check if document has signature
                if ($document->signatures()->first()) {

                    // delete document signature
                    $document->signatures()->first()->delete();
                }

                // create new signature
                $document->signatures()->create([
                    'qr' => $document->qr,
                    'user_id' => $user->id
                ]);

                $signatures = [
                    [
                        'position' => $user->employee->position->position->name ?? '',
                        'qr'       => $document->signatures->firstWhere('user_id', $user->id)?->qr,
                        'name'     => $user->name,
                    ],
                ];


                $pattern = array('#<p(.*?)>(.*?)</p>#is', '#<span (.*?)>(.*?)</span>#is');
                $replacement = array('$2<br/>', '$2');

                $text = $doc['meta']['content'];

                // check if file exist
                if (Storage::disk('docs')->exists($document->path)) {
                    // delete exist file
                    Storage::disk('docs')->delete($document->path);
                }

                // create new PDF documents
                Storage::disk('docs')->put($document['path'], PDF::loadView('docs::manage.report', compact('doc', 'signatures', 'text'))->output());
            }
            return redirect()->next()->with('success', 'Dokumen dengan nama <strong>' . $document->label . '</strong> berhasil diperbarui, terima kasih.');
        }
        return redirect()->fail();
    }

    /**
     * delete document
     */
    public function destroy(Document $document)
    {
        if ($document->delete()) {
            return redirect()->next()->with('success', 'Dokumen telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * preview document
     */
    public function download(Document $document)
    {
        return $document->show();
    }
}
