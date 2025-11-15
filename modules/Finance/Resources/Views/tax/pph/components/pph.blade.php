<table class="calc-table table align-middle">
    <tr>
        <td class="table-active" colspan="100%">
            <strong>Penghitungan PPh 21</strong>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div>Total penghasilan (Bruto)</div>
            <div class="small text-muted"><cite>Terbilang: <span class="bruto-month-inword">nol</span> rupiah.</cite></div>
        </td>
        <td>
            <div class="input-group">
                <input type="number" name="pkp" class="form-control calc-bruto-month-subtotal-input text-end" value="0" onchange="calculatePph(event)" onkeyup="calculatePph(event)">
            </div>
        </td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">
            <div>PTKP Status </div>
            <div class="small text-muted"><cite>Status PTKP berdasarkan status pernikahan dan jumlah tanggungan.</cite></div>
        </td>
        <td>
            <input type="text" name="category" class="form-control calc-ptkp-category-input text-end" value="">
        </td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">
            <div>Kategori </div>
            <div class="small text-muted"><cite>Kategori TER berdasarkan status pernikahan dan jumlah tanggungan.</cite></div>
        </td>
        <td>
            <input type="text" name="ter_category" class="form-control calc-ter-category-input text-end" value="">
        </td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">
            <div>Tarif Pajak </div>
            <div class="small text-muted"><cite>Prosentase berdasarkan kategori dan besaran upah.</cite></div>
            <div class="small text-muted">Terbilang: <span class="calc-ter-value-inword">nol</span> persen.</cite></div>
        </td>
        <td>
            <input type="number" step="0.001" name="rate" class="form-control calc-ter-value-input text-end" value="">
        </td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">
            <div>PPh 21 terhutang </div>
            <div class="small text-muted"><cite>Besaran PPh 21 terutang berdasarkan kategori.</cite></div>
            <div class="small text-muted">Terbilang: <span class="calc-ter-amount-inword">nol</span> rupiah.</cite></div>
        </td>
        <td>
            <input type="number" step="0.001" name="amount" class="form-control calc-ter-amount-input text-end" value="0" onchange="generatePph(event)" onkeyup="generatePph(event)">
        </td>
        <td></td>
    </tr>
</table>
