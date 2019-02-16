var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
    showLeftPush = document.getElementById( 'open-side-menu' ),
    body = document.body;

showLeftPush.onclick = function() {
    classie.toggle( this, 'active' );
    classie.toggle( body, 'cbp-spmenu-push-toright' );
    classie.toggle( menuLeft, 'cbp-spmenu-open' );
    disableOther( 'open-side-menu' );
};


function disableOther( button ) {

    if( button !== 'open-side-menu' ) {
        classie.toggle( showLeftPush, 'disabled' );
    }

}
