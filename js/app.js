const compiso = [
    {
      name: "Aurora Medel Ruiz",
      desc: "Creativa y entusiasta la programación, siempre buscando aprender y crecer en todos proyectos para dejar huella.",
      image: "./images/aurora.jpg"
    },
    {
      name: "Paula Fernandez Cañas", 
      desc: "Descripción de Persona 2.",
      image: "../images/paula.jpeg"
    },
    {
      name: "Carlos Sanchez Perez",
      desc: "Apasionado y entusiasta de la tecnología y el desarrollo web, siempre buscando aprender, crear y crecer con cada proyecto",
      image: "../images/carlos.jpeg"
    },
    {
      name: "Luis Mesa Perez",
      desc: "Fanático de la programación dispuesto siempre a expandir conocimientos, mejorar como profesional y embarcarse en nuevos proyectos",
      image: "../images/luis.jpeg"
    }
  ];
  
  let cont = 0;
  
  function mostrar(index) {
    cont = index;
    datosCarrusel();
    document.getElementById('modal').style.display = 'flex';
  }
  
  function ocultar() {
    document.getElementById('modal').style.display = 'none';
  }
  
  function datosCarrusel() {
    const compi = compiso[cont];
    document.getElementById('modalImage').src = compi.image;
    document.getElementById('modalName').textContent = compi.name;
    document.getElementById('modalDesc').textContent = compi.desc;
  }
  
  function prevcompi() {
    cont = (cont - 1 + compiso.length) % compiso.length;
    datosCarrusel();
  }
  
  function nextcompi() {
    cont = (cont + 1) % compiso.length;
    datosCarrusel();
  }