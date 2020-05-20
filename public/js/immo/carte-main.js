window.onbeforeunload = function () {
  window.localStorage.clear(); // Nettoyage du localstorage si rechargement de la page
}

//  |**********|
//  |* DIVERS *|
//  |**********|

// Panneau latéral
var slider = $("#panneau-lateral").slideReveal({
  push: false,
  position: "left",
  width: 600,
  speed: 400,
  trigger: $(".handle"),
  show: function () {
    var element = document.getElementById("limit-svg");
    // Instead of .addClass("newclass")
    element.setAttribute("style", "width: calc(100vw - 602px); margin-left: 600px;");

    redimensionCarte();
  },
  shown: function (obj) {
    obj.find(".handle").html('<i class="icone-custom icon-left-open">&#xe800;</i>');
    obj.addClass("left-shadow-overlay");

    redimensionCarte();
  },
  hide: function () {
    var element = document.getElementById("limit-svg");
    // Instead of .addClass("newclass")
    element.setAttribute("style", "width: calc(100vw - 100px);");
    redimensionCarte();
  },
  hidden: function (obj) {
    obj.find(".handle").html('<i class="icone-custom icon-right-open">&#xe801;</i>');
    obj.removeClass("left-shadow-overlay");

    var element = document.getElementById("limit-svg");
    // Instead of .addClass("newclass")
    element.setAttribute("style", "");
    redimensionCarte();

  }
});

function zoomMaison(event) { // zommer sur la maison selectionnée: id maison liste > id élément calque > coodrdonées élément calque > panTo coordonées
  var e = event.currentTarget.id.replace("liste-Log", ""); // liste-LogX > X
  activeArea(e); // On surligne la maison sélectionnée

  // On remet à zéro le zoom de la carte, on la fait tenir en entier dans le cadre et on la met au zoom 1, initial
  panZoomSRP.fit();
  panZoomSRP.center();
  panZoomSRP.zoom(1);

  var zoneCarteElements = Array.from(document.getElementsByClassName("svg-pan-zoom_viewport"));
  zoneCarteElements.map(element => element.classList.add("animationCarte"));

  // http://jsfiddle.net/mgv5fuyw/3/
  var bb = $("#Log" + e)[0].getBBox(); // getBBox() permet d'obtenir le plus petit rectangle dans lequel notre élément peut tenir
  var vbb = panZoomSRP.getSizes().viewBox; // getSizes().viewBox permet d'obtenir la hauteur et la largeur du cadre de la carte
  var x = vbb.width / 2 - bb.x - bb.width / 2;
  var y = vbb.height / 2 - bb.y - bb.height / 2;
  var rz = panZoomSRP.getSizes().realZoom;
  var zoom = 5;
  panZoomSRP.panBy({ x: x * rz, y: y * rz });
  panZoomSRP.zoom(zoom);

  document.getElementById('limit-svg').addEventListener("transitionend", function () {
    zoneCarteElements.map(element => element.classList.remove("animationCarte"));
  });
}

// Polyfill classlist pour adapter la fonction à tous les navigateurs
if ("document" in self) {
  if (!("classList" in document.createElement("_")) || document.createElementNS && !("classList" in document.createElementNS("http://www.w3.org/2000/svg", "g"))) {
    (function (t) {
      "use strict";
      if (!("Element" in t)) return;
      var e = "classList",
        i = "prototype",
        n = t.Element[i],
        s = Object,
        r = String[i].trim || function () { return this.replace(/^\s+|\s+$/g, "") },
        a = Array[i].indexOf || function (t) {
          var e = 0,
            i = this.length;
          for (; e < i; e++) { if (e in this && this[e] === t) { return e } }
          return -1
        },
        o = function (t, e) {
          this.name = t;
          this.code = DOMException[t];
          this.message = e
        },
        l = function (t, e) { if (e === "") { throw new o("SYNTAX_ERR", "An invalid or illegal string was specified") } if (/\s/.test(e)) { throw new o("INVALID_CHARACTER_ERR", "String contains an invalid character") } return a.call(t, e) },
        c = function (t) {
          var e = r.call(t.getAttribute("class") || ""),
            i = e ? e.split(/\s+/) : [],
            n = 0,
            s = i.length;
          for (; n < s; n++) { this.push(i[n]) }
          this._updateClassName = function () { t.setAttribute("class", this.toString()) }
        },
        u = c[i] = [],
        f = function () { return new c(this) };
      o[i] = Error[i];
      u.item = function (t) { return this[t] || null };
      u.contains = function (t) { t += ""; return l(this, t) !== -1 };
      u.add = function () {
        var t = arguments,
          e = 0,
          i = t.length,
          n, s = false;
        do {
          n = t[e] + "";
          if (l(this, n) === -1) {
            this.push(n);
            s = true
          }
        } while (++e < i);
        if (s) { this._updateClassName() }
      };
      u.remove = function () {
        var t = arguments,
          e = 0,
          i = t.length,
          n, s = false,
          r;
        do {
          n = t[e] + "";
          r = l(this, n);
          while (r !== -1) {
            this.splice(r, 1);
            s = true;
            r = l(this, n)
          }
        } while (++e < i);
        if (s) { this._updateClassName() }
      };
      u.toggle = function (t, e) {
        t += "";
        var i = this.contains(t),
          n = i ? e !== true && "remove" : e !== false && "add";
        if (n) { this[n](t) }
        if (e === true || e === false) { return e } else { return !i }
      };
      u.toString = function () { return this.join(" ") };
      if (s.defineProperty) {
        var h = { get: f, enumerable: true, configurable: true };
        try { s.defineProperty(n, e, h) } catch (d) {
          if (d.number === -2146823252) {
            h.enumerable = false;
            s.defineProperty(n, e, h)
          }
        }
      } else if (s[i].__defineGetter__) { n.__defineGetter__(e, f) }
    })(self)
  } else {
    (function () {
      "use strict";
      var t = document.createElement("_");
      t.classList.add("c1", "c2");
      if (!t.classList.contains("c2")) {
        var e = function (t) {
          var e = DOMTokenList.prototype[t];
          DOMTokenList.prototype[t] = function (t) {
            var i, n = arguments.length;
            for (i = 0; i < n; i++) {
              t = arguments[i];
              e.call(this, t)
            }
          }
        };
        e("add");
        e("remove")
      }
      t.classList.toggle("c3", false);
      if (t.classList.contains("c3")) {
        var i = DOMTokenList.prototype.toggle;
        DOMTokenList.prototype.toggle = function (t, e) { if (1 in arguments && !this.contains(t) === !e) { return e } else { return i.call(this, t) } }
      }
      t = null
    })()
  }
}

// Code pour lier les maisons à la liste, et vice-versa
var map = document.querySelector("main"),
  paths = map.querySelectorAll("#Logement>polygon, #Logement>rect"),
  pathsGroups = map.querySelectorAll("#Logement>g>polygon, #Logement>g>rect"),
  markers = map.querySelectorAll("#groupeMarqueurs>use");
void 0 === NodeList.prototype.forEach && (NodeList.prototype.forEach = function (e) {
  [].forEach.call(this, e)
});

var activeArea = function (e) {
  if (Math.max(document.documentElement.clientWidth, window.innerWidth || 0) > 800) { // Si jamais on est sur un écran large (ordi) on appelle le panneau latéral
    slider.slideReveal("show");
  }
  map.querySelectorAll(".is-active").forEach(function (e) {
    e.classList.remove("is-active")
  })
  if (e !== undefined) {
    var element = document.querySelector("#liste-Log" + e);

    element.classList.add("is-active");
    document.querySelector("#Log" + e).classList.add("is-active");

    // smooth scroll to element and align it to the center of scrollable parent
    element.scrollIntoView({ behavior: 'smooth', block: 'center' });

    var nodes = document.querySelector("#Log" + e).children; // Appliquer une classe à tous les enfants d'un élément
    for (var i = 0; i < nodes.length; i++) {
      nodes[i].classList.add("is-active");
    }
  }
};

paths.forEach(function (e) {
  e.addEventListener("click", function () {
    var e = this.id.replace("Log", ""); // LogX > X
    activeArea(e);
  })
}), pathsGroups.forEach(function (e) {
  e.addEventListener("click", function () {
    var e = this.id.slice(3, -2); // LogX-Y > X /!\ Cela peut poser problème lorsqu'il y a plus de 10 enfants dans un groupe
    activeArea(e);
  })
}), markers.forEach(function (e) {
  e.addEventListener("click", function () {
    var e = this.id.slice(3, -9); // LogX-marqueur > X
    activeArea(e);
  })
});

var clicExpoMaison = JSON.parse(localStorage.getItem('clicExpoMaison'));

if (clicExpoMaison != null) {
  function filtrerParRecherche(liste, critere) {
    // .filter() permet de garder les éléments passant le test passé en paramêtre
    return liste.filter(
      candidat => candidat.adresse === critere.adresse
    );
  }

  var maisonSelectionnee = filtrerParRecherche(adresses, clicExpoMaison);

  let calqueMaison = maisonSelectionnee.map(a => a.id_calque);
  // ["Log0", "Log1", "Log2", "Log3", "Log4", "Log5", "Log6", "Log7", "Log8", "Log9", "Log10", "Log11", "Log12", "Log13", "Log14", "Log15"]
  var initial = document.getElementById('initial');
  var marqueurInitial = document.getElementById('marqueurInitial');

  for (const calque of calqueMaison) {

    var calqueSvg = document.getElementById(calque);
    var groupeMarqueurs = document.getElementById('groupeMarqueurs');

    let bb = initial.getBBox();
    let boiteCalqueSvg = calqueSvg.getBBox();
    let centreCalqueSvgX = boiteCalqueSvg.x + boiteCalqueSvg.width / 2;
    let centreCalqueSvgY = boiteCalqueSvg.y + boiteCalqueSvg.height / 2;

    let x = centreCalqueSvgX - bb.x - bb.width / 2;
    let y = centreCalqueSvgY - bb.y - bb.height / 2;

    var marqueur = marqueurInitial.cloneNode(true);  // <use id="theUse" xlink:href="#initial" />
    marqueur.setAttributeNS(null, "id", calque + "-marqueur");
    marqueur.setAttributeNS(null, "style", "visibility: visible;");
    marqueur.setAttributeNS(null, "class", "marqueursMaisons");
    marqueur.setAttributeNS(null, "x", x);
    marqueur.setAttributeNS(null, "y", y);

    groupeMarqueurs.appendChild(marqueur);

    var e = calque.replace("Log", ""); // LogX > X
    activeArea(e);
  }
}

// Recherche des maisons correspondant à la requête sur index.php 
// https://stackoverflow.com/questions/52489741/filter-json-object-array-on-multiple-values-or-arguments-javascript

var paramRecherche = JSON.parse(localStorage.getItem('paramRecherche'));
var paramExtra = JSON.parse(localStorage.getItem('paramExtra'));

if (paramRecherche != null) {

  // On retire les valeurs vides "null" du tableau
  Object.keys(paramRecherche).forEach((key) => (paramRecherche[key] == null) && delete paramRecherche[key]);

  // CE QU'IL RESTE A FAIRE; ANALYSER OFFRE ET PRIX
  function filtrerParRecherche(liste, critere, critere2) {
    // .filter() permet de garder les éléments passant le test passé en paramêtre
    return liste.filter(
      // Candidat au test = chaque objet avec chaque critère, et il passe le test si chaque test suivant est passé
      candidat => Object.keys(critere).every(
        // On vérifie si le candidat contient le paramêtre passé "critere" avec .indexOf()
        key => candidat[key].toLowerCase().indexOf(critere[key].toLowerCase()) > -1,
      )
        && parseInt(candidat.prix) <= parseInt(critere2.prixmax)
        && parseInt(candidat.prix) >= parseInt(critere2.prixmin)
        && Math.sign(parseInt(candidat.offre)) <= Math.sign(parseInt(critere2.offre)) // Grâce à Math.sign, on obtient une seule valeur quelque soit la valeur entrée, tout dépend de son signe. "<=" car on souhaite inclure les bâtiments en promo, même si l'utilisateur ne cherche pas uniquement ceux en promo
        && (candidat.appartement == critere2.appartement || candidat.maison == critere2.maison || candidat.industrie == critere2.industrie || candidat.commerce == critere2.commerce)
    );
  }

  var listeMaisons = filtrerParRecherche(adresses, paramRecherche, paramExtra);

  let listeCalques = listeMaisons.map(a => a.id_calque);
  // ["Log0", "Log1", "Log2", "Log3", "Log4", "Log5", "Log6", "Log7", "Log8", "Log9", "Log10", "Log11", "Log12", "Log13", "Log14", "Log15"]
  var initial = document.getElementById('initial');
  var marqueurInitial = document.getElementById('marqueurInitial');

  for (const calque of listeCalques) {

    var calqueSvg = document.getElementById(calque);
    var groupeMarqueurs = document.getElementById('groupeMarqueurs');

    let bb = initial.getBBox();
    let boiteCalqueSvg = calqueSvg.getBBox();
    let centreCalqueSvgX = boiteCalqueSvg.x + boiteCalqueSvg.width / 2;
    let centreCalqueSvgY = boiteCalqueSvg.y + boiteCalqueSvg.height / 2;

    let x = centreCalqueSvgX - bb.x - bb.width / 2;
    let y = centreCalqueSvgY - bb.y - bb.height / 2;

    var marqueur = marqueurInitial.cloneNode(true);  // <use id="theUse" xlink:href="#initial" />
    marqueur.setAttributeNS(null, "id", calque + "-marqueur");
    marqueur.setAttributeNS(null, "style", "visibility: visible;");
    marqueur.setAttributeNS(null, "class", "marqueursMaisons");
    marqueur.setAttributeNS(null, "x", x);
    marqueur.setAttributeNS(null, "y", y);

    groupeMarqueurs.appendChild(marqueur);
  }
}

// FIN DU CODE UTILISANT LE FICHIER JSON DES ADRESSES

// Barre de recherche

function rechercher() {
  // Declare variables
  if (Math.max(document.documentElement.clientWidth, window.innerWidth || 0) > 800) {
    var input = document.getElementById('barre-recherche');
  } else {
    var input = document.getElementById('barre-recherche-mobile');
  }

  var filter, ul, li, titlehouse, i, txtValue;
  filter = input.value.toUpperCase();
  ul = document.getElementById("maisonListe");
  li = ul.getElementsByTagName('li');

  $("#groupeMarqueurs").children(":not(#marqueurInitial)").remove();

  if (filter != "") {
    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
      titlehouse = li[i].getElementsByTagName("h3")[0];
      txtValue = titlehouse.textContent || titlehouse.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        li[i].style.display = "";

        var calque = li[i].id.replace("liste-", ""); // liste-LogX > LogX

        // ["Log0", "Log1", "Log2", "Log3", "Log4", "Log5", "Log6", "Log7", "Log8", "Log9", "Log10", "Log11", "Log12", "Log13", "Log14", "Log15"]
        var initial = document.getElementById('initial');
        var marqueurInitial = document.getElementById('marqueurInitial');

        var calqueSvg = document.getElementById(calque);
        var groupeMarqueurs = document.getElementById('groupeMarqueurs');

        let bb = initial.getBBox();
        let boiteCalqueSvg = calqueSvg.getBBox();
        let centreCalqueSvgX = boiteCalqueSvg.x + boiteCalqueSvg.width / 2;
        let centreCalqueSvgY = boiteCalqueSvg.y + boiteCalqueSvg.height / 2;

        let x = centreCalqueSvgX - bb.x - bb.width / 2;
        let y = centreCalqueSvgY - bb.y - bb.height / 2;

        var marqueur = marqueurInitial.cloneNode(true);  // <use id="theUse" xlink:href="#initial" />
        marqueur.setAttributeNS(null, "id", calque + "-marqueur");
        marqueur.setAttributeNS(null, "style", "visibility: visible;");
        marqueur.setAttributeNS(null, "class", "marqueursMaisons");
        marqueur.setAttributeNS(null, "x", x);
        marqueur.setAttributeNS(null, "y", y);

        groupeMarqueurs.appendChild(marqueur);

      } else {
        li[i].style.display = "none";
      }
    }
  } else {
    for (i = 0; i < li.length; i++) {
      li[i].style.display = "";
    }
  }
}

$(window).on("resize", function () {
  if (Math.max(document.documentElement.clientWidth, window.innerWidth || 0) > 800) {
    slider.slideReveal("hide");
  }
}).resize();

// Obtention du navigateur, et message aux non-utilisateurs de Chrome

navigator.browserSpecs = (function () {
  var ua = navigator.userAgent, tem,
    M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
  if (/trident/i.test(M[1])) {
    tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
    return { name: 'IE', version: (tem[1] || '') };
  }
  if (M[1] === 'Chrome') {
    tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
    if (tem != null) return { name: tem[1].replace('OPR', 'Opera'), version: tem[2] };
  }
  M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
  if ((tem = ua.match(/version\/(\d+)/i)) != null)
    M.splice(1, 1, tem[1]);
  return { name: M[0], version: M[1] };
})();

if (navigator.browserSpecs.name == 'Firefox') {
  alert('Cette carte ne fonctionne pas correctement sous Firefox, merci d\'utiliser un navigateur compatible comme Google Chrome');
};