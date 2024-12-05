let animation = {
    revealDistance: 150,
    intialOpacity: 0,
    transitionDelay: 0,
    transitionDuration: '2s',
    transitionProperty: 'all',
    transitionTimingFunction: 'ease'
  }
  
  window.addEventListener('scroll', function() {
    let revealableContainers = document.querySelectorAll(".revealable");
  
    for(i = 0; i < revealableContainers.length; i++){
      let windowHeight = window.innerHeight;
      let topOfRevealableContainer = revealableContainers[i].getBoundingClientRect().top;
      
      if (topOfRevealableContainer < windowHeight - animation.revealDistance) {
        revealableContainers[i].classList.add("active");
      } else {
        revealableContainers[i].classList.remove("active");
      }
    }
  });