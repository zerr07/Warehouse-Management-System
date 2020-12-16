{include file='header.tpl'}
<style>
    .list-group-item:hover{
        color: #1d7097 !important;
        text-decoration: underline !important;
    }
</style>
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-12 text-left">
                    <h5>Tree list</h5>
                    <ul class="list-group">
                        <a href="/elkoTree.php"><li class="list-group-item">Elko tree</li></a>
                        <a href="/TreeMinuvalik.php"><li class="list-group-item">Minuvalik tree</li></a>
                        <a href="/TreeMobilux.php"><li class="list-group-item">Mobilux tree</li></a>
                        <a href="/TreeOki.php"><li class="list-group-item">Okidoki tree</li></a>
                        <a href="/TreeOsta.php"><li class="list-group-item">Osta tree</li></a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    window.addEventListener("load", function (){
        setPageTitle("Tree links");
    })
</script>
{include file='footer.tpl'}
