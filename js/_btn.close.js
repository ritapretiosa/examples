/*... скрипт кнопки закрытия чего-либо */

var SYSTEM = SYSTEM || {};


SYSTEM.BtnClose = function() {
    this.active = function(e) {
        var el = document.querySelectorAll('.btn_close');

        if(el != null && el != undefined) {
            for(var k = 0; k < el.length; el++) {
                el[k].addEventListener('click', function(e) {
                    var close = e.currentTarget;
                    if(close.classList.contains('close_bar')) close.classList.remove('close_bar');
                    else close.classList.add('close_bar');
                })
            }
        }
    }
    this.active();
}

window.addEventListener('DOMContentLoaded', function(e) {
    new SYSTEM.BtnClose();
})