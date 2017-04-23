var wbbOpt = {
    buttons: "bold,italic,underline,headers,|,img,link,|,code,span,quote,|,bullist,numlist,|,fontcolor,removeFormat",
    lang: "ru",
    resize_maxheight: '400',
    txtAreaId: 'wbbEditorContent',
    imgupload: true,
    img_uploadurl: '/uploads/image',
    allButtons: {
        img: {
            title: CURLANG.img,
            buttonHTML: '<span class="fonticon ve-tlb-img1">\uE006</span>',
            hotkey: 'ctrl+shift+1',
            addWrap: true,
            modal: {
                title: CURLANG.modal_img_title,
                width: "600px",
                tabs: [
                    {
                        title: CURLANG.modal_img_tab1,
                        input: [
                            {param: "SRC", title: CURLANG.modal_imgsrc_text, validation: '^.*?\.(jpg|png|gif|jpeg)$'},
                            {param: "ALT", title: CURLANG.modal_imgalt_text}
                        ]
                    },
                    {//The second tab
                        title: CURLANG.modal_img_tab2,
                        html: '<div id="imguploader"> <form id="fupform" class="upload" action="{img_uploadurl}" method="post" enctype="multipart/form-data" target="fupload"><input type="hidden" name="iframe" value="1"/><input type="hidden" name="idarea" value="{txtAreaId}" /><div class="fileupload"><input id="fileupl" class="file" type="file" name="img" /><button id="nicebtn" class="wbb-button">' + CURLANG.modal_img_btn + '</button> </div> </form> </div><iframe id="fupload" name="fupload" src="about:blank" frameborder="0" style="width:0px;height:0px;display:none"></iframe></div>'
                    }
                ],
                onLoad: this.imgLoadModal
            },
            transform: {
                '<img src="{SRC}" />': "[img]{SRC}[/img]",
                '<img src="{SRC}" alt="{ALT}" />': "[img alt={ALT}]{SRC}[/img]",
                '<img src="{SRC}" alt="{ALT}" width="{WIDTH}" height="{HEIGHT}"/>': "[img alt={ALT} width={WIDTH},height={HEIGHT}]{SRC}[/img]"
            }
        },
        span: {
            buttonText: 'span',
            title: 'span class=*',
            modal: {
                title: 'Добавить span с классом',
                width: "300px",
                tabs: [
                    {
                        input: [
                            {param: "SELTEXT", title: 'Текст внутри span', type: "div"},
                            {param: "CLASS", title: 'Название класса(ов)', validation: '^.'}
                        ]
                    }
                ]
            },
            transform: {
                '<span class="{CLASS}">{SELTEXT}</span>': '[span class="{CLASS}"]{SELTEXT}[/span]'
            }
        },
        headers: {
            type: 'select',
            title: 'Заголовок h2-h6',
            options: "header_2,header_3,header_4,header_5,header_6",
            transform: {
                //'<h{HEADER}>{SELTEXT}</h{HEADER}>':'[h{HEADER}]{SELTEXT}[/h{HEADER}]'
            }
        },
        link: {
            title: CURLANG.link,
            buttonHTML: '<span class="fonticon ve-tlb-link1">\uE007</span>',
            hotkey: 'ctrl+shift+2',
            modal: {
                title: CURLANG.modal_link_title,
                width: "500px",
                tabs: [
                    {
                        input: [
                            {param: "SELTEXT", title: CURLANG.modal_link_text, type: "div"},
                            {param: "URL", title: CURLANG.modal_link_url, validation: '^.'}
                        ]
                    }
                ]
            },
            transform: {
                '<a href="{URL}">{SELTEXT}</a>': "[url={URL}]{SELTEXT}[/url]",
                '<a href="{URL}">{URL}</a>': "[url]{URL}[/url]"
            }
        },
        // select headers
        header_2: {
            title: 'Заголовок h2',
            buttonText: "h2",
            excmd: 'headers',
            exvalue: "2",
            transform: {
                '<h2>{SELTEXT}</h2>': '[h2]{SELTEXT}[/h2]'
            }
        },
        header_3: {
            title: 'Заголовок h3',
            buttonText: "h3",
            excmd: 'headers',
            exvalue: "3",
            transform: {
                '<h3>{SELTEXT}</h3>': '[h3]{SELTEXT}[/h3]'
            }
        },
        header_4: {
            title: 'Заголовок h4',
            buttonText: "h4",
            excmd: 'headers',
            exvalue: "4",
            transform: {
                '<h4>{SELTEXT}</h4>': '[h4]{SELTEXT}[/h4]'
            }
        },
        header_5: {
            title: 'Заголовок h5',
            buttonText: "h5",
            excmd: 'headers',
            exvalue: "5",
            transform: {
                '<h5>{SELTEXT}</h5>': '[h5]{SELTEXT}[/h5]'
            }
        },
        header_6: {
            title: 'Заголовок h6',
            buttonText: "h6",
            excmd: 'headers',
            exvalue: "6",
            transform: {
                '<h6>{SELTEXT}</h6>': '[h6]{SELTEXT}[/h6]'
            }
        },
    }
};