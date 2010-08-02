function aceAdminCreateIconMenu() {
    var iconMenu = new Element('div', {
        'id': 'adm_icon_menu',
        'html': '<a href="'+DIR_WEB_ROOT+'/admin/"><img src="'+DIR_WEB_ROOT+'/plugins/aceadminpanel/templates/skin/default/images/icon_menu3.png" alt="" /></a>',
        'styles': {
            'height': '20px',
            'width': '100px',
            'position': 'fixed',
            'top': '0px',
            'left': '2px'
        }
    });
    var body = document.getElementsByTagName('body');

    iconMenu.inject(body[0]);
}
function aceAdminInit() {
    aceAdminCreateIconMenu();
}

window.addEvent('domready', function() {
    aceAdminInit();
});
