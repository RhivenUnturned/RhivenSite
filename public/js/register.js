$(function () {

  $('form').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      type: 'post',
      url: '/register',
      data: $('form').serialize(),
      success: function () {
        alert('form was submitted');
      }
    });
  });
});

// Code By Webdevtrick ( https://webdevtrick.com )
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

window.addEventListener('load', setFormHeight, false);
window.addEventListener('resize', setFormHeight, false);