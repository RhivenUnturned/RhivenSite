//  |************|
//  |* LA CARTE *|
//  |************|

var eventsHandler;

eventsHandler = {
    haltEventListeners: ['touchstart', 'touchend', 'touchmove', 'touchleave', 'touchcancel'],
    init: function (options) {
        var instance = options.instance,
            initialScale = 1,
            pannedX = 0,
            pannedY = 0
        // Init Hammer
        // Listen only for pointer and touch events
        this.hammer = Hammer(options.svgElement, {
            inputClass: Hammer.SUPPORT_POINTER_EVENTS ? Hammer.PointerEventInput : Hammer.TouchInput
        })
        // Enable pinch
        this.hammer.get('pinch').set({ enable: true })
        // Handle double tap
        this.hammer.on('doubletap', function (ev) {
            instance.zoomIn()
        })
        // Handle pan
        this.hammer.on('panstart panmove', function (ev) {
            // On pan start reset panned variables
            if (ev.type === 'panstart') {
                pannedX = 0
                pannedY = 0
            }
            // Pan only the difference
            instance.panBy({ x: ev.deltaX - pannedX, y: ev.deltaY - pannedY })
            pannedX = ev.deltaX
            pannedY = ev.deltaY
        })
        // Handle pinch
        this.hammer.on('pinchstart pinchmove', function (ev) {
            // On pinch start remember initial zoom
            if (ev.type === 'pinchstart') {
                initialScale = instance.getZoom()
                instance.zoomAtPoint(initialScale * ev.scale, { x: ev.center.x, y: ev.center.y })
            }
            instance.zoomAtPoint(initialScale * ev.scale, { x: ev.center.x, y: ev.center.y })
        })
        // Prevent moving the page on some devices when panning over SVG
        options.svgElement.addEventListener('touchmove', function (e) { e.preventDefault(); });
    },
    destroy: function () {
        this.hammer.destroy()
    }
}

var beforePan

beforePan = function (oldPan, newPan) {
    var stopHorizontal = false,
        stopVertical = false,
        gutterWidth = 400,
        gutterHeight = 400,
        // Computed variables
        sizes = this.getSizes(),
        leftLimit = -((sizes.viewBox.x + sizes.viewBox.width) * sizes.realZoom) + gutterWidth,
        rightLimit = sizes.width - gutterWidth - (sizes.viewBox.x * sizes.realZoom),
        topLimit = -((sizes.viewBox.y + sizes.viewBox.height) * sizes.realZoom) + gutterHeight,
        bottomLimit = sizes.height - gutterHeight - (sizes.viewBox.y * sizes.realZoom)

    customPan = {}
    customPan.x = Math.max(leftLimit, Math.min(rightLimit, newPan.x))
    customPan.y = Math.max(topLimit, Math.min(bottomLimit, newPan.y))

    return customPan
}

var initial = document.getElementById('initial');
var marqueurInitial = document.getElementById('marqueurInitial');

// Get instance
var panZoomSRP = svgPanZoom('#limit-svg', {
    zoomEnabled: true,
    zoomScaleSensitivity: 0.7,
    fit: true,
    center: 1,
    minZoom: 0.8,
    maxZoom: 11,
    beforePan: beforePan,
    onZoom: function (currZoom) {
        // Code qui permet aux marqueurs de rester en place lors d'un zoom
        // Franchement je saurais pas l'expliquer, mais je l'ai trouvé là: https://stackoverflow.com/a/54810687 et un peu modifié

        var els = document.querySelectorAll('.thePath');
        for (var i = 0; i < els.length; i++) {
            els[i].setAttributeNS(null, "transform", "scale(" + 1 / currZoom + ")");
        }
        let bb = initial.getBBox();

        var marqueurs = document.querySelectorAll('.marqueursMaisons');
        for (var i = 0; i < marqueurs.length; i++) {

            var calqueId = marqueurs[i].id.replace("-marqueur", "");
            var calqueSvg = document.getElementById(calqueId);

            let boiteCalqueSvg = calqueSvg.getBBox();
            let centreCalqueSvgX = boiteCalqueSvg.x + boiteCalqueSvg.width / 2;
            let centreCalqueSvgY = boiteCalqueSvg.y + boiteCalqueSvg.height / 2;

            let x = centreCalqueSvgX - bb.x - bb.width / 2;
            let y = centreCalqueSvgY - bb.y - bb.height / 2;
            marqueurs[i].setAttributeNS(null, "x", x);
            marqueurs[i].setAttributeNS(null, "y", y);
        }
    },
    customEventsHandler: eventsHandler
});

$(window).resize(function () {
    panZoomSRP.resize();
    panZoomSRP.fit();
    panZoomSRP.center();
})

var redimensionCarte = function () {
    panZoomSRP.resize();
};