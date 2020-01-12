/*.... скрипт, выдернутый из формы настроек сайта. Создает анимацию
		кнопки сохранения (ожидание), аяксом запускает php файл сохранения настроек */ 

if(System.Event)
    var sys_event = new System.Event();
    
    var container = document.querySelector(".__container");
    if(container) {
        container.classList.add("active");
    }
    
    var form = document.querySelector(".setting_form");
    var btn = document.querySelector(".setting_save");
    if(form) {
        form.addEventListener("submit", function(e) {
            btn.querySelector("div").classList.remove("loaded");
            btn.querySelector("div").classList.add("load");
            $.ajax({
                type: "POST",
                url: "/bitrix/admin/_system/save.php",
                data: $(this).serialize(),
                crossDomain: true,
                success: function(msg){
                    setTimeout(function() {
                        btn.querySelector("div").classList.remove("load");
                        btn.querySelector("div").classList.add("loaded");
                    }, 1000)
                }
            });
            e.preventDefault();
        });
    }
    
    (function() {
        [].slice.call( document.querySelectorAll( '.system_tab' ) ).forEach( function( el ) {
            new CBPFWTabs( el );
        });
    })();
    
sys_event.sendEvent("modalcontentloaded", window);
