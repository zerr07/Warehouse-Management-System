<div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="image_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="carousel-control-prev-icon" onclick="prev_image()" aria-hidden="true"></span>
                <span class="carousel-control-next-icon" onclick="next_image()" aria-hidden="true"></span>
            </div>
            <div class="modal-body" id="image_modal_body">

            </div>
        </div>
    </div>
</div>

<script>
    var previous_img_slider;
    var next_img_slider;
    var modal;
    window.onload = function (){
        modal = $('#image_modal');
        modal.on('show.bs.modal', function (e) {
            let img = $(e.relatedTarget.firstElementChild).clone();
            draw_img(img);
            set_prev_next_images(e.relatedTarget);
        });
    }
    function next_image(){
        let img = $(next_img_slider).clone();
        draw_img(img);
        set_prev_next_images(next_img_slider.parentElement);
    }
    function prev_image(){
        let img = $(previous_img_slider).clone();
        draw_img(img);
        set_prev_next_images(previous_img_slider.parentElement);
    }
    function set_prev_next_images(middle_image){
        if (middle_image.parentElement.nextSibling.nextSibling === null){ // next element
            next_img_slider = middle_image.parentElement.parentElement.firstElementChild.firstElementChild.firstElementChild;
        } else {
            next_img_slider = middle_image.parentElement.nextSibling.nextSibling.firstElementChild.firstElementChild;
        }
        if (middle_image.parentElement.previousSibling.previousSibling === null){ // previous element
            previous_img_slider = middle_image.parentElement.parentElement.lastChild.previousSibling.firstElementChild.firstElementChild;
        } else {
            previous_img_slider = middle_image.parentElement.previousSibling.previousSibling.firstElementChild.firstElementChild;
        }
    }
    function draw_img(img){
        $("#image_modal_body").html(img[0]);
    }

</script>