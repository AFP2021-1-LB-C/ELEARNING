@include('layout.sidebar')
<div class="adj-pagecontent">
@include('layout.header')
<h2>{{$topic}}</h2>
<div>
{!! $content !!}
</div>
</div>
<!--Embed youtube video linket kell feltölteni-->
{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        $("figure.media").each(function(){
            var url = $(this).find("oembed").attr("url");
            $(this)[0].outerHTML ='<iframe width="560" height="315" src="'+url+'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
        })
});
</script> --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $("figure.media").each(function () {
            var url = $(this).find("oembed").attr("url");
            url = url.replace("https://", "").replace("www.youtube.com", "").replace("/watch?v=", "").replace("/embed/", "")
            $(this)[0].outerHTML = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' + url + '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
        })
    });
</script>
@include('layout.footer')
