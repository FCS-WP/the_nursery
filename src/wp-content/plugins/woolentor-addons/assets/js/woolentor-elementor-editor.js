;(function($){

    var wooLentorElementorEditorMode = {

        init: function(){
            // Promosion Widget
            if ( !woolentorSetting.hasPro || !_.isEmpty( woolentorSetting.proWidgets ) ){
                this.addPromutionWidget();
                this.handleDialogBox();
            }
        },

        getWidgetInfo: function( value, key ) {
            let widgetObj = woolentorSetting.proWidgets.find(function (widget, index) {
                if ( widget[key] == value ) return true;
            });
            return widgetObj;
        },

        addPromutionWidget: function(){
            elementor.hooks.addFilter("panel/elements/regionViews", function (panel) {

                if ( woolentorSetting.hasPro || _.isEmpty( woolentorSetting.proWidgets ) ) return panel;

                let freeCategoryIndex,
                    proCategory     = "woolentor_addons_pro",
                    elementsView    = panel.elements.view,
                    categoriesPannelView  = panel.categories.view,
                    widgets         = panel.elements.options.collection,
                    allCategories   = panel.categories.options.collection,
                    woolentorProcategroy = [];


                    _.each(woolentorSetting.proWidgets, function (widget, index) {
                        widgets.add({ 
                            name: widget.name, 
                            title: widget.title, 
                            icon: widget.icon, 
                            categories: [proCategory], 
                            editable: !1 
                        });
                    });

                    widgets.each(function (widget) {
                        widget.get("categories")[0] === proCategory && woolentorProcategroy.push(widget);
                    });

                    freeCategoryIndex = allCategories.findIndex({
                        name: "woolentor-addons"
                    });

                    if( freeCategoryIndex === 0 ){
                        allCategories.add({ 
                            name: proCategory, 
                            title: wp.i18n.__("ShopLentor Pro",'woolentor'), 
                            icon: "woolentor-category-icon", 
                            defaultActive: 1, 
                            sort: !0, 
                            hideIfEmpty: !0, 
                            items: woolentorProcategroy, 
                            promotion: !1 
                        }, { at: freeCategoryIndex + 1 });
                    }else{
                        freeCategoryIndex && allCategories.add({ 
                            name: proCategory, 
                            title: wp.i18n.__("ShopLentor Pro",'woolentor'), 
                            icon: "woolentor-category-icon", 
                            defaultActive: 1, 
                            sort: !0, 
                            hideIfEmpty: !0, 
                            items: woolentorProcategroy, 
                            promotion: !1 
                        }, { at: freeCategoryIndex + 1 });
                    }

                return panel;

            });
        },

        handleDialogBox: function(){

            parent.document.addEventListener("mousedown", function (e) {
        
                let allWidgets = parent.document.querySelectorAll(".elementor-element--promotion");
                
                if ( allWidgets.length > 0 && !woolentorSetting.hasPro ) {
                    for ( let i = 0; i < allWidgets.length; i++ ) {
                        if ( allWidgets[i].contains( e.target ) ) {

                            let promotionDialog = parent.document.querySelector("#elementor-element--promotion__dialog"),
                                icon = allWidgets[i].querySelector(".icon > i"),
                                widgetTitleWrap = allWidgets[i].querySelector(".title-wrapper > .title"),
                                widgetTitle = widgetTitleWrap.innerHTML,
                                widgetObject = wooLentorElementorEditorMode.getWidgetInfo(widgetTitle, 'title'),
                                actionURL = widgetObject?.action_url,
                                widgetDescription = widgetObject?.description ? sprintf( widgetObject.description, widgetTitle ) : sprintf( wp.i18n.__('Use %s widget and dozens more pro features to extend your toolbox and build sites faster and better.', 'woolentor'), widgetTitle );


                            if ( icon.classList.contains('woolentor-pro-promotion') ) {

                                promotionDialog.classList.add('woolentor-pro-widget');
                                promotionDialog.querySelector(".dialog-buttons-message").innerHTML = widgetDescription;

                                if (promotionDialog.querySelector("a.woolentor-pro-dialog-button-action") === null) {

                                    let buttonElement = document.createElement("a"),
                                        buttonText = document.createTextNode( wp.i18n.__('Upgrade Now', 'woolentor') );

                                    buttonElement.classList.add(
                                        "dialog-button",
                                        "dialog-action",
                                        "dialog-buttons-action",
                                        "elementor-button",
                                        "woolentor-pro-dialog-button-action"
                                    );

                                    buttonElement.setAttribute("href", actionURL);
                                    buttonElement.setAttribute("target", "_blank");
                                    buttonElement.appendChild(buttonText);

                                    promotionDialog.querySelector(".dialog-buttons-action").insertAdjacentHTML("afterend", buttonElement.outerHTML);
                                    promotionDialog.querySelector(".woolentor-pro-dialog-button-action").style.backgroundColor = "#93003f";
                                    promotionDialog.querySelector(".woolentor-pro-dialog-button-action").style.textAlign = "center"; 
                                    promotionDialog.querySelector(".elementor-button.go-pro.dialog-buttons-action").classList.add('woolentor-elementor-pro-hide');

                                } else {
                                    promotionDialog.querySelector(".woolentor-pro-dialog-button-action").style.textAlign = "center"; 
                                    promotionDialog.querySelector(".elementor-button.go-pro.dialog-buttons-action").classList.add('woolentor-elementor-pro-hide');
                                }
                            } else {
                                promotionDialog?.classList.remove('woolentor-pro-widget');
                                if ( promotionDialog.querySelector(".woolentor-pro-dialog-button-action") !== null ) {
                                    promotionDialog.querySelector(".woolentor-pro-dialog-button-action").style.display = "none";
                                }
                            }
                            // Break The loop if target element has found
                            break;
                        }
                    }
                }


            });
        },

    };

    wooLentorElementorEditorMode.init();
  
})(jQuery);