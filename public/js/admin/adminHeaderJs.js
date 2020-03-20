
var sideNaveOpen = true;
var inSearch = true;
var sideNavMenu = 0;
var sideNavMenuCounter = 0;
var windowWidth = $(window).width();


function toggleNav(){
    if(sideNaveOpen == true){
        if(windowWidth > 1000)
            $('#mainBody').removeClass('goRight');

        $('#mainNav').removeClass('goRight');
        $('#mySidenav').css('left', '-250px');
        sideNaveOpen = false;
    }
    else{
        if(windowWidth > 1000)
            $('#mainBody').addClass('goRight');

        $('#mainNav').addClass('goRight');
        $('#mySidenav').css('left', '0px');
        sideNaveOpen = true;
    }
}

function showSubSideNavMenu(_element){
    $('#backSideNavMenuDiv').css('display', 'flex');
    $(_element).next().css('display', 'block');
    setTimeout(function () {
        $(_element).next().css('left', '0px');
        $('#backSideNavMenuName').text($(_element)[0]['text'].trim());
        sideNavMenu = _element;
        sideNavMenuCounter++;
    }, 50);
}

function backSideNavMenu(){
    sideNavMenuCounter--;

    $(sideNavMenu).next().css('left', '250px');
    setTimeout(function(){
        $(sideNavMenu).next().css('display', 'none');
        if(sideNavMenuCounter == 0)
            $('#backSideNavMenuDiv').css('display', 'none');
        else{
            sideNavMenu = $(sideNavMenu).parent().parent().prev();
            $('#backSideNavMenuName').text($(sideNavMenu)[0]['text'].trim());
        }
    }, 50);
}

$(document).ready(function(){
    if(windowWidth < 1000) {
        $('#mainBody').removeClass('goRight');
    }
})