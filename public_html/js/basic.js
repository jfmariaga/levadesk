
    // valida para que no se pinte textualmente NULL en la tabla
    function print( val ){
        return val ? val : '';
    }

    function redirec( url ){
        window.location.href = url
    }

    function numberFormat( number ){
        // return number;
        // return new Intl.NumberFormat('de-DE').format(number);
        return '$' + number.toLocaleString();
    }

    
    function getDay(date){
        const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        const day = dias[ date.getDay() ];
        return day;
    }

    function formatDateTime(date){
        const months = ["Jun", "Feb", "Mar","Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
        let formatted_date = date.getDate() + " " + months[date.getMonth()] + " del " + date.getFullYear() + " " + date.toLocaleTimeString();
        return formatted_date;
    }

    function formatDate(date){
        console.log(date);
        const months = ["Jun", "Feb", "Mar","Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
        let formatted_date = date.getDate() + " " + months[date.getMonth()] + " del " + date.getFullYear();
        return formatted_date;
    }

    // ventana emergente
    function openIframe(src, clase = '', fn_destroy = null) {
        Fancybox.show(
            [{
                src: src,
                type: 'iframe',
                preload: true,
                //   scrolling: false,//no probado
                autoSize: true, //para que si damos clase en CC se ponga el boton cerrar automático donde debe ser
                autoFocus: true,
    
    
            }, ], {
                closeButton: true, // lo ocultamos porque no funciona en responsive, sabrá el putas por qué
                smallBtn: false,
                mainClass: clase,
                template: {
                    spinner: '',
                },
                on: {
                    init: () => { // se ejecuta al iniciar
                        // blockPage();
                    },
                    shouldClose: () => { // se ejecuta cuando se cierra el fancy
                        if (fn_destroy && fn_destroy != '') {
                            fn_destroy();
                        }
                        // unblockPage();
                    },
                    done: () => { // cuando ya se ha cargado y mostrado el fancy
                    }
                },
            }
        );
    
    }