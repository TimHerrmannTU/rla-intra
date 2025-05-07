<script>
jQuery(document).ready(function($) {
    $(".cat-item").each(function() {
        var text = $(this).html()
        text = text.replaceAll("(", "<sup class='count'>")
        text = text.replaceAll(")", "</sup>")
        $(this).html(text)
    })
})
</script>