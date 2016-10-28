(function (window, document) {

    var layout   = document.getElementById('layout'),
        menu     = document.getElementById('menu');

    function toggleClass(element, className) {
        var classes = element.className.split(/\s+/),
            length = classes.length,
            i = 0;

        for(; i < length; i++) {
          if (classes[i] === className) {
            classes.splice(i, 1);
            break;
          }
        }

        if (length === classes.length) {
            classes.push(className);
        }

        element.className = classes.join(' ');
    }

    $('#menuLink').click(function (e) {
        var active = 'active';

        e.preventDefault();
        toggleClass(layout, active);
        toggleClass(menu, active);
        toggleClass(menuLink, active);
    });

}(this, this.document));
