<style>
    .backToTopButton{
        position: fixed;
        left: 15px;
        bottom: -60px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        box-shadow: 1px 1px 5px 2px black;
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 5px;
        transition: .3s;
        background: white;
        z-index: 9;
    }

    .backToTopButton.showBut{
        bottom: 15px;
    }
</style>

<div class="backToTopButton" onclick="goToTopPage()">
    <div class="arrow bottom" style="transform: rotate(-135deg);"></div>
</div>

<script>
    $(window).on('scroll', e => {
        if($(window).scrollTop() >= 300)
            $('.backToTopButton').addClass('showBut');
        else
            $('.backToTopButton').removeClass('showBut');
    });
    $('#mainBodyJournal').on('scroll', e => {
        if($('#mainBodyJournal').scrollTop() >= 300)
            $('.backToTopButton').addClass('showBut');
        else
            $('.backToTopButton').removeClass('showBut');
    });
    function goToTopPage() {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $("#mainBodyJournal").animate({ scrollTop: 0 }, "slow");
        return false;
    }
</script>
