  // const openMenu = openMenu.addEventListener('click', openMenuMobile)
  
  
  // function openMenuMobile(){
  //        console.log('ouvrir le menu');
  //   };


// const itemsWithSubmenus = document.querySelectorAll('nav ul li');

// itemsWithSubmenus.forEach(item => {
//     const submenu = item.querySelector('ul');
//     if (submenu) {
//       item.addEventListener('mouseenter', () => {
//         submenu.classList.add('visible');
//       });
//       item.addEventListener('mouseleave', () => {
//         submenu.classList.remove('visible');
//       });
//     }
//   });

  
 
  function openMenu(){
      document.querySelector('nav').classList.add('open');
      // document.querySelector('.close_nave').classList.add('open');
  }
  function closeMenu(){
      document.querySelector('nav').classList.remove('open');
      // document.querySelector('.close_nave').classList.remove('open');
  }

  

    


   
 
    
