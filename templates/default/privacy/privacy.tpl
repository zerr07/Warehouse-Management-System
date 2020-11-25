{include file='header.tpl'}
<div class="row mt-3">
    <div class="col-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-toggle="tab" data-target="#ru" role="tab" aria-controls="ru" aria-selected="true">RU</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-toggle="tab" data-target="#en" role="tab" aria-controls="en" aria-selected="false">EN (Google translate)</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="ru" role="tabpanel" aria-labelledby="ru-tab">{include file='privacy/ru.tpl'}</div>
            <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">{include file='privacy/en.tpl'}</div>
        </div>

    </div>
</div>
{include file='footer.tpl'}
