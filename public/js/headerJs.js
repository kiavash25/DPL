
var sideNaveOpen = false;
var inSearch = true;
var sideNavMenu = 0;
var sideNavMenuCounter = 0;
function openNav() {
    $('#backBlackSideNav').css('display', 'block');
    setTimeout(function(){
        $('#mySidenav').css('left', '0px');
        sideNaveOpen = true;
    }, 50);
}

function closeNav() {
    $('#backBlackSideNav').css('display', 'none');
    $('#mySidenav').css('left', '-250px');
    sideNaveOpen = false;
}

function showSubSideNavMenu(_element){
    $('#backSideNavMenuDiv').css('display', 'block');
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
    }, 100);
}

function showNavSearchMobile(){
    inSearch = true;
    $('.searchBackBlack').show();
    $('#searchNavMobile').css('width', '100%');
    $('.mobileNavSearchInput').focus();
    $('body').css('overflow-y', 'hidden');
}
function closeNavSearchMobile(){
    inSearch = false;
    clearResult();
    $('.searchBackBlack').hide();
    $('#searchNavMobile').css('width', '0%');
    $(".mobileNavSearchInput").val('');
    $('body').css('overflow-y', 'auto');
}

function clearResult(){
    setTimeout(function(){
        $('.searchResult').html('');
        $('.searchResult').hide();
    }, 100);
}

$(window).on('click', function(e){
    if(sideNaveOpen){
        if($(e.target).attr('id') == 'backBlackSideNav')
            closeNav();
    }
});
