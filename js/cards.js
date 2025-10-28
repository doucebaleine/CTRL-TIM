(function(){
  // Simple stack behaviour: close top card -> remove it -> shift others up -> add new back card
  const stack = document.querySelector('.poster-stack');
  if(!stack) return;

  function createCard(id, src, isTop){
    const card = document.createElement('div');
    card.className = 'poster-card';
    if(isTop) card.classList.add('poster-top');
    else if(id===1) card.classList.add('poster-mid');
    else card.classList.add('poster-back');
    card.dataset.id = id;

    const btn = document.createElement('button');
    btn.className = 'poster-close';
    btn.setAttribute('aria-label','Fermer');
    btn.innerText = '✕';
    btn.addEventListener('click', onClose);

    const img = document.createElement('img');
    img.src = src;
    img.alt = '';

    card.appendChild(btn);
    card.appendChild(img);
    return card;
  }

  // initial data (use theme images as placeholders)
  const posters = [
    '/wp-content/themes/CTRL-TIM/images/logo.svg',
    '/wp-content/themes/CTRL-TIM/images/effetTitre.svg',
    '/wp-content/themes/CTRL-TIM/images/effetTitre.svg',
    '/wp-content/themes/CTRL-TIM/images/effetTitre.svg'
  ];

  // helper to add a new back card
  function addNewBack(){
    const src = posters[Math.floor(Math.random()*posters.length)];
    const newCard = createCard(Date.now(), src, false);
    newCard.classList.add('new-back');
    stack.appendChild(newCard);
    // force layout then animate in
    requestAnimationFrame(()=>{
      newCard.classList.add('appear');
      // after appear remove helper class
      setTimeout(()=> newCard.classList.remove('new-back','appear'), 450);
    });
  }

  function closeTop(top){
    if(!top) return;
    top.classList.add('closing');
    top.addEventListener('transitionend', function handler(){
      top.removeEventListener('transitionend', handler);
      top.remove();
      shiftStack();
      addNewBack();
    });
  }

  function onClose(e){
    e.stopPropagation();
    const btn = e.currentTarget;
    const card = btn.closest('.poster-card');
    if(!card) return;
    const top = stack.querySelector('.poster-top');
    // if clicked card is already top, close it
    if(card === top){
      closeTop(top);
      return;
    }
    // else rotate classes so clicked card becomes top, animate then close
    const mid = stack.querySelector('.poster-mid');
    const back = stack.querySelector('.poster-back');
    if(card.classList.contains('poster-mid')){
      // top -> back, mid -> top, back -> mid
      if(top){ top.classList.remove('poster-top'); top.classList.add('poster-back'); }
      if(mid){ mid.classList.remove('poster-mid'); mid.classList.add('poster-top','animate-to-top'); }
      if(back){ back.classList.remove('poster-back'); back.classList.add('poster-mid'); }
      setTimeout(()=>{ if(mid) mid.classList.remove('animate-to-top'); closeTop(stack.querySelector('.poster-top')); }, 520);
      return;
    }
    if(card.classList.contains('poster-back')){
      // rotate: top->mid, mid->back, back->top
      if(top){ top.classList.remove('poster-top'); top.classList.add('poster-mid'); }
      if(mid){ mid.classList.remove('poster-mid'); mid.classList.add('poster-back'); }
      card.classList.remove('poster-back'); card.classList.add('poster-top','animate-to-top');
      setTimeout(()=>{ card.classList.remove('animate-to-top'); closeTop(stack.querySelector('.poster-top')); }, 520);
      return;
    }
  }

  function shiftStack(){
    const mid = stack.querySelector('.poster-mid');
    const back = stack.querySelector('.poster-back');
    if(mid){ mid.classList.remove('poster-mid'); mid.classList.add('animate-to-top','poster-top'); setTimeout(()=> mid.classList.remove('animate-to-top'),500); }
    if(back){ back.classList.remove('poster-back'); back.classList.add('poster-mid'); }
  }

  // attach close handler for all existing close buttons
  stack.querySelectorAll('.poster-close').forEach(btn=> btn.addEventListener('click', onClose));

  // Safety: if user clicks on stack background, close top as well
  stack.addEventListener('click', function(e){
    if(e.target === stack) return; // ignore background
  });

})();
