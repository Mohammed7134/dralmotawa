<div class="col-md-4 item ">
    <div class="shadow-lg p-2 my-3 rounded blog_post_container">
        @include("shared.blogPost", ["displayed"=>$wisdom->text, "wisdom"=>$wisdom, "title"=>""])
    </div>
</div>
@include("shared.fullCard")