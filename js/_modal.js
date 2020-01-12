/* ... Скрипт создания модального окна по нажатию на кнопку,
		содержимое окна подгружается аяксом из файла, указанного в атрибуте src ... */

var System = System || {};
System.Modal = function() {

    var param = {
        overlay: {
            load: {},
            container: {
                close: {},
                content: {},
            }
        }
    }
    
    var sys_mod = this;
    
    sys_mod.findButtons = function() {
        return document.querySelectorAll(".__modal");
    }
    
    sys_mod.createModal = function(el) {
        var obj = {
            load: {},
            container: {
                close: {},
                content: {},
            }
        };
        
        obj = document.createElement("div");
        obj.className = "modal__window " + el.getAttribute("__mod_overlay");

        // === Close Modal
        obj.addEventListener('click', function(e) {
            if(e.target.classList.contains("modal__window")) {
                obj.container.classList.remove("active");
                setTimeout(function(){
                    obj.parentNode.removeChild(obj);
                }, 500);
            }
        });
        
        obj.load = document.createElement("div");
        obj.load.className = "square-spinner";
        obj.appendChild(obj.load);
        
        obj.container = document.createElement("div");
        obj.container.className = "__container " + el.getAttribute("__mod_container");
        
        obj.container.close = document.createElement("div");
        obj.container.close.className = "__btn_close p-3 fa fa-times";

        // Close Modal
        obj.container.close.addEventListener('click', function(e) {
            obj.container.classList.remove("active");
            setTimeout(function(){
                obj.parentNode.removeChild(obj);
            },500);
        });
        
        obj.container.content = document.createElement("div");
        obj.container.content.className = "__content " + el.getAttribute("__mod_content");
        
        obj.appendChild(obj.container);
        obj.container.appendChild(obj.container.close);
        obj.container.appendChild(obj.container.content);

        return obj;
    }
    
    var buttons = sys_mod.findButtons();
    if(buttons) {
        for(var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener("click", function(e) {
                var el = e.currentTarget;
                e.preventDefault();
                el.modal = sys_mod.createModal(el);
                document.body.appendChild(el.modal);
                el.modal.style.display = "flex";
                
                
                setTimeout(function() {
                    BX.ajax.post(
                        //link,
                        el.getAttribute("__src"),
                        {},
                        function(data){
                            el.modal.container.content.innerHTML = data;
                            
                            el.modal.load.style.display = "none";
                            el.modal.container.classList.add("active");
                        }
                    ); 
                },1000);
                
            });
        }
    }
};
window.addEventListener("DOMContentLoaded", function() {
    new System.Modal();
});
 