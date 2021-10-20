/** Button Animation */
(function (webigoHelper, d) {
  const _event = webigoHelper?.eventManager;

  const buttons = document.querySelectorAll(".wbg-button");

  _event.bulkAttachEvent({
    el: buttons,
    ev: _event.type.click,
    cb: animateButtons,
  });

  function animateButtons(e) {
    let x = e.clientX - e.target.offsetLeft;
    let y = e.clientY - e.target.offsetTop;

    let ripples = d.createElement("span");
    ripples.setAttribute("class", "btn-animate");
    ripples.style.left = x + "px";
    ripples.style.top = y + "px";
    this.insertBefore(ripples, this.firstChild);

    setTimeout(() => {
      ripples.remove();
    }, 700);
  }
})(webigoHelper, document);
