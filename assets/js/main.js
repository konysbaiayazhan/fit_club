// 1. Мобильді мәзір
const burger = document.querySelector('.burger');
const navLinks = document.querySelector('.nav-links');
if (burger && navLinks) {
  burger.addEventListener('click', () => navLinks.classList.toggle('open'));
}

// 2. Клиенттік тексеру (жылдам кері байланыс).
//    Негізгі тексеру серверде (includes/functions.php) жасалады.
const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function setError(field, message) {
  field.classList.add('invalid');
  field.querySelector('.error').textContent = message;
}

function validateForm(form) {
  form.querySelectorAll('.field').forEach(f => f.classList.remove('invalid'));
  let ok = true;
  const get = name => form.elements[name];
  const box = el => el.closest('.field');

  if (get('full_name') && get('full_name').value.trim().length < 3) {
    setError(box(get('full_name')), 'Аты-жөніңізді толық жазыңыз (кемінде 3 таңба)');
    ok = false;
  }
  if (!emailRe.test(get('email').value.trim())) {
    setError(box(get('email')), 'Email дұрыс емес');
    ok = false;
  }
  if (get('phone') && !/^\+?\d{10,15}$/.test(get('phone').value.replace(/[\s()-]/g, ''))) {
    setError(box(get('phone')), 'Телефон нөмірін дұрыс жазыңыз');
    ok = false;
  }
  if (get('password').value.length < 6) {
    setError(box(get('password')), 'Пароль кемінде 6 таңба болуы керек');
    ok = false;
  }
  if (get('password2') && get('password2').value !== get('password').value) {
    setError(box(get('password2')), 'Парольдер сәйкес келмейді');
    ok = false;
  }
  return ok;
}

document.querySelectorAll('form[data-validate]').forEach(form => {
  form.addEventListener('submit', e => {
    // Қате болса жібермейміз; дұрыс болса форма серверге (PHP) кетеді
    if (!validateForm(form)) e.preventDefault();
  });
});
