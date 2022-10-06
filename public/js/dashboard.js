// Select Body
const bodyEle = document.body;

// Select Switch Toggle
const switchToggleDash = document.querySelector('.toggle-switch');
console.log(switchToggleDash, document.querySelector('.toggle-switch'), bodyEle)
// When (switchToggle) has (click) Event
switchToggleDash.addEventListener('click', function() {
    // Toggle Class (body_theme_light) On (bodyEle)
    bodyEle.classList.toggle('body_theme_light');
});
