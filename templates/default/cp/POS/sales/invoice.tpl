<div hidden>
    {foreach $sales as $sale}
    <div id="form">
        <div class="row">
            <div class="col-9">
                <img src="aaaaaaa.png">
            </div>
            <div class="col-1">
                <b>
                    ARVE<br />
                    Kuupäev
                </b>
            </div>
            <div class="col-2">
                <b>
                    {$sale.arveNr} <br />
                    {$sale.date}
                </b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="border: 1px solid black">
                <div class="row">
                    <div class="col-2">
                        <b>Müüja:</b><br />
                        Reg Nr<br />
                        Aadress<br />
                        KMKR
                    </div>
                    <div class="col-10">
                        AZ Trade OÜ<br />
                        12474341<br />
                        J. Koorti tn 2-122 Tallinn Harjumaa 13623<br />
                        EE101681917
                        (‿|‿)
                    </div>
                </div>
            </div>
            <div class="col-6" style="border: 1px solid black">
                <div class="row">
                    <div class="col-2">
                        <b>Ostja:</b><br />
                        Makseviis
                    </div>
                    <div class="col-10">
                        {$sale.ostja}<br />
                        {if $sale.card == "0.00"}
                            Sularaha
                        {elseif $sale.cash == "0.00"}
                            Kaart
                        {else}
                            Sularaha + Kaart
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-2 offset-6">
                <b>Maksetähtaeg:</b><br />
                Viivis:
            </div>
            <div class="col-4">
                <b>8/6/2020</b><br />
                0.05%
            </div>
        </div>
        <div class="row">
            <div class="col-1" style="border: 1px solid black">Kood</div>
            <div class="col-3" style="border: 1px solid black">Teenuste/kaupade nimetus</div>
            <div class="col-1" style="border: 1px solid black">Ühik</div>
            <div class="col-1" style="border: 1px solid black">Maht</div>
            <div class="col-2" style="border: 1px solid black">Hind KM-ga</div>
            <div class="col-2" style="border: 1px solid black">Hind KM-ta</div>
            <div class="col-2" style="border: 1px solid black">Kokku KM-ta</div>
        </div>
        {foreach $desc as $prod}
            <div class="row">
                <div class="col-1" style="border: 1px solid black">{$prod.tag}</div>
                <div class="col-3" style="border: 1px solid black">{$prod.name}</div>
                <div class="col-1" style="border: 1px solid black">tk</div>
                <div class="col-1" style="border: 1px solid black">{$prod.quantity}</div>
                <div class="col-2" style="border: 1px solid black">{$prod.basePrice}</div>
                <div class="col-2" style="border: 1px solid black">{($prod.basePrice/1.2)|round:4}</div>
                <div class="col-2" style="border: 1px solid black">{($prod.price/1.2)|round:4}</div>
            </div>
        {/foreach}

        <div class="row">
            <div class="col-2 offset-6">
                Summa<br />
                Käibemaks 20%<br />
                <b>Kokku</b><br />
                Valuuta
            </div>
            <div class="col-4">
                {($sale.sum/1.2)|round:2}<br />
                {($sale.sum - $sale.sum/1.2)|round:2}<br />
                <b>{$sale.sum}</b><br />
                <b>EUR</b>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                AZ TRADE OÜ, J. Koorti tn 2-122, 13623, Tallinn<br />
                Reg nr: 12474341 , KMKR: EE101681917<br />
                E-post: info@bigshop.ee<br />
                Telefon: +37258834435
            </div>
            <div class="col-6">
                SWEDBANK EE792200221057460362<br />
                SWIFT: HABAEE2X
            </div>
        </div>
    </div>
    {/foreach}
</div>