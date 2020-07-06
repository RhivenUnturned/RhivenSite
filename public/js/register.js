$('html').bind('keypress', function (e) {
  if (e.keyCode == 13) {
    return false;
  }
});

const DOMstrings = {
  stepsBtnClass: 'multisteps-form__progress-btn',
  stepsBtns: document.querySelectorAll(`.multisteps-form__progress-btn`),
  stepsBar: document.querySelector('.multisteps-form__progress'),
  stepsForm: document.querySelector('.multisteps-form__form'),
  stepsFormInputs: document.querySelectorAll('.multisteps-form__input'),
  stepFormPanelClass: 'multisteps-form__panel',
  stepFormPanels: document.querySelectorAll('.multisteps-form__panel'),
  stepPrevBtnClass: 'js-btn-prev',
  stepNextBtnClass: 'js-btn-next'
};

// Code By Webdevtrick ( https://webdevtrick.com )
const removeClasses = (elemSet, className) => {
  elemSet.forEach(elem => {
    elem.classList.remove(className);
  });
};

const findParent = (elem, parentClass) => {
  let currentNode = elem;

  while (!currentNode.classList.contains(parentClass)) {
    currentNode = currentNode.parentNode;
  }

  return currentNode;
};

const getActiveStep = elem => {
  return Array.from(DOMstrings.stepsBtns).indexOf(elem);
};

const setActiveStep = activeStepNum => {
  removeClasses(DOMstrings.stepsBtns, 'js-active');

  DOMstrings.stepsBtns.forEach((elem, index) => {
    if (index <= activeStepNum) {
      elem.classList.add('js-active');
    }
  });
};

const getActivePanel = () => {
  let activePanel;
  DOMstrings.stepFormPanels.forEach(elem => {

    if (elem.classList.contains('js-active')) {
      activePanel = elem;
    }
  });

  return activePanel;
};

const setActivePanel = activePanelNum => {
  removeClasses(DOMstrings.stepFormPanels, 'js-active');

  DOMstrings.stepFormPanels.forEach((elem, index) => {
    if (index === activePanelNum) {
      elem.classList.add('js-active');
      setFormHeight(elem);
    }
  });

};

const formHeight = activePanel => {
  const activePanelHeight = activePanel.offsetHeight;
  DOMstrings.stepsForm.style.height = `${activePanelHeight}px`;
};

const setFormHeight = () => {
  const activePanel = getActivePanel();

  formHeight(activePanel);
};

DOMstrings.stepsBar.addEventListener('click', e => {
  let allGood = true;
  const eventTarget = e.target;
  if (!eventTarget.classList.contains(`${DOMstrings.stepsBtnClass}`)) {
    return;
  }

  const activePanel = getActivePanel();
  const activeStep = getActiveStep(eventTarget);
  let activeInputs = activePanel.querySelectorAll('.multisteps-form__input');

  for (let element of activeInputs) {
    if (!element.reportValidity()) {
      allGood = false;
      break;
    }
  }

  if (allGood) {
    setActiveStep(activeStep);
    setActivePanel(activeStep);
  }
});

DOMstrings.stepsForm.addEventListener('click', e => {
  let allGood = true;
  const eventTarget = e.target;

  if (!(eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`) || eventTarget.classList.contains(`${DOMstrings.stepNextBtnClass}`))) {
    return;
  }

  const activePanel = findParent(eventTarget, `${DOMstrings.stepFormPanelClass}`);
  let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel);
  let activeInputs = activePanel.querySelectorAll('.multisteps-form__input');

  for (let element of activeInputs) {
    if (!element.reportValidity()) {
      allGood = false;
      break;
    }
  }

  if (eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`)) {
    activePanelNum--;
  } else if (allGood) {
    activePanelNum++;
  }

  setActiveStep(activePanelNum);
  setActivePanel(activePanelNum);
});

let sex,sent = false;
$(document).ready(function () {

  document.getElementById('registration-form').addEventListener('submit', e => {
    e.preventDefault();

    var radios = document.getElementsByName('sex');

    console.log(radios);

    for (var i = 0, length = radios.length; i < length; i++) {
      if (radios[i].checked) {
        sex = radios[i].value;
        break;
      }
    }

    if (!sent) {
      axios
        .post(document.getElementById('registration-form').action, {
          'name': document.querySelector('input[name="name"]').value,
          'email': document.querySelector('input[name="email"]').value,
          'password': document.querySelector('input[name="password"]').value,
          'password_confirmation': document.querySelector('input[name="password_confirmation"]').value,
          'firstname': document.querySelector('input[name="firstname"]').value,
          'lastname': document.querySelector('input[name="lastname"]').value,
          'sex': sex,
          'age': document.querySelector('input[name="age"]').value,
          'story': document.querySelector('textarea[name="story"]').value
        })
        .then((response) => {
          clearErrors()
          sent = true
          $("#registration-success").toast('show')
        })
        .catch((error) => {
          clearErrors()
          setActiveStep(0)
          setActivePanel(0)
          const errorParent = document.getElementById('error-parent')
          errorParent.innerHTML = `<div class="alert alert-danger mb-4" role="alert">Une ou plusieurs erreurs se sont produites</div>`

          const errors = error.response.data.errors
          for (let item of Object.keys(errors)) {
            var error = errors[item];
            const itemDOM = document.getElementById(item)
            // show the error message
            itemDOM.insertAdjacentHTML('afterend', `<div class="text-danger">${error}</div>`)
            // highlight the form control with the error
            itemDOM.classList.add('is-invalid')
          }
        });
    }
  });

  document.getElementById('steam-btn').addEventListener('click', e => {
    openSteamWindow();

    var steamAPICall = window.setInterval(function () {
      // GET request for user's steamId
      axios({
        method: 'get',
        url: '/user/steamid',
        responseType: 'json'
      })
        .then(function (response) {
          if (response.data.steamId != null) {
            steamWindow.close()
            clearInterval(steamAPICall)
            var steamLinkButton = document.getElementById('steam-btn')
            var steamSuccessButton = document.createElement("button")
            steamSuccessButton.classList.add("btn", "btn-success")
            steamSuccessButton.disabled = true
            steamSuccessButton.setAttribute('type', 'button')
            steamLinkButton.replaceWith(steamSuccessButton);
            steamSuccessButton.innerText = "Compte lié !"
          }
        })
        .catch((error) => {
          console.log(error)
        });
    }, 2700);

  });

  document.getElementById('discord-btn').addEventListener('click', e => {
    openDiscordWindow();

    var discordAPICall = window.setInterval(function () {
      // GET request for user's discordid
      axios({
        method: 'get',
        url: '/user/discordid',
        responseType: 'json'
      })
        .then(function (response) {
          if (response.data.discordId != null) {
            discordWindow.close()
            clearInterval(discordAPICall)
            var discordLinkButton = document.getElementById('discord-btn')
            var discordSuccessButton = document.createElement("button")
            discordSuccessButton.classList.add("btn", "btn-success")
            discordSuccessButton.disabled = true
            discordSuccessButton.setAttribute('type', 'button')
            discordLinkButton.replaceWith(discordSuccessButton);
            discordSuccessButton.innerText = "Compte lié !"
          }
        })
        .catch((error) => {
          console.log(error)
        });
    }, 2700);

  });
});

function clearErrors() {
  // remove error alert
  var node = document.getElementById('error-parent');
  if (node) {
    node.querySelectorAll('*').forEach(n => n.remove());
  }
  // remove all error messages
  const errorMessages = document.querySelectorAll('.text-danger')
  errorMessages.forEach((element) => element.textContent = '')
  // remove all form controls with highlighted error text box
  const formControls = document.querySelectorAll('.form-control')
  formControls.forEach((element) => element.classList.remove('is-invalid'))
};

window.addEventListener('load', setFormHeight, false);
window.addEventListener('resize', setFormHeight, false);

var steamWindow, discordWindow;
function openSteamWindow() {
  steamWindow = window.open('/steam', '_blank', 'left=50vh,top=50%,height=570,width=520,scrollbars=yes');
}

function openDiscordWindow() {
  discordWindow = window.open('/discord', '_blank', 'left=50vh,top=50%,height=570,width=520,scrollbars=yes');
}