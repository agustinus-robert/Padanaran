<?php

namespace Modules\Admin\DataTables;

use Modules\Admin\Models\Post;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Builder;

class CustomDatatables
{
    public function columnBuilder($array = [], $builder)
    {
        if (count($array)) {
            $columnNum = [];
            $param = [];
            $call = [];

            foreach ($array['column'] as $key => $val2) {
                $kolom = Column::make($val2['data'])
                    ->name($val2['name'] ?? $val2['data'])
                    ->title($val2['title'] ?? ucfirst($val2['title']))
                    ->orderable($val2['orderable'] ?? true)
                    ->searchable($val2['searchable'] ?? true);


                $columnNum[] = $kolom;
            }

            $builder->setTableId('dataTableBuilder');
            if (isset($array['ajax']['url'])) {
                $param['url'] = $array['ajax']['url'];
                $param['type'] = 'GET';
            }

            if (isset($array['ajax']['script'])) {
                $param['data'] = $array['ajax']['script'];
            }


            $builder->ajax($param);

            if (isset($array['parameters'])) {
                $call['paging'] = true;
                $call['searching'] = true;
                $call['info'] = false;
                $call['searchDelay'] = 350;
            }


            if (isset($array['parameters']['drawCallback'])) {
                $call['drawCallback'] = $array['parameters']['drawCallback'];
            }

            $builder->drawCallbackWithLivewire()
                ->buttons(
                    Button::make('create'),
                    Button::make('export'),
                    Button::make('print'),
                    Button::make('reset'),
                    Button::make('reload')
                )
                ->parameters($call);


            $builder->columns($columnNum);
            return $builder;
        }
    }
}
