{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-5 text-center text-white fullHeight" >
        <div class="container">

            <div class="row">
                <div class="col-md-12 text-left">
                    {* Default tree *}
                    <div class="table-responsive">
                        <table class="table-responsive">
                            <thead>
                            <tr>
                                <td style="width: 50px;">id</td>
                                <td style="width: 250px;">Name</td>
                                <td style="width: 50px;">Code</td>
                            </tr>
                            </thead>
                            <tbody>
                            {assign var="count" value=0}
                            {foreach $categories as $item}
                                <tr>
                                    <td>{$count}</td>
                                    <td>{$item.name}</td>
                                    <td>{$item.code}</td>
                                </tr>
                                {assign var="count" value=$count+1}
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    window.addEventListener("load", function (){
        setPageTitle("Elko tree");
    })
</script>
{include file='footer.tpl'}
