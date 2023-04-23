
const colonne = [
    { name: 'Type', index: 0, isFilter: false },
    { name: 'Pathologies', index: 1, isFilter: false },
    { name: 'Méridien', index: 2, isFilter: false },
    { name: 'Description', index: 3, isFilter: false }
  ]



function filtreSymp(columns,indice,sel) {

    //console.log(columns);
    columns[indice].isFilter = true;

    const filterColumns = columns.filter(c => c.isFilter).map(c => c.index)
    const trs = document.querySelectorAll(`#myTable tr:not(.header)`)
    const filter = document.querySelector(sel).value

    if (filter === null || filter == "" || filter == "Types" || filter == "Pathologie" || filter == "Méridien") {columns[indice].isFilter = false}
    else {
      const regex = new RegExp(filter)
      const isFoundInTds = td => regex.test(td.innerHTML)
      const isFound = childrenArr => childrenArr.some(isFoundInTds)
      const setTrStyleDisplay = ({ style, children }) => {
          if (isFound([...filterColumns.map(c => children[c])])){
              if (style.display == 'none'){}
              else {style.display = ''}
          }
          else{
              style.display = 'none'
          }
      }

      
      
      trs.forEach(setTrStyleDisplay)
  }
}



const filterType = () => {

  const trs = document.querySelectorAll(`#myTable tr:not(.header)`)

  const setClear = ({ style, children }) => {
    style.display = ''
  }
  trs.forEach(setClear)

  filtreSymp(colonne,0,"#type")
  filtreSymp(colonne,1,"#pathologie")
  filtreSymp(colonne,2,"#meridien")
  filtreSymp(colonne,3,"#recherchebar")
}

const parseCookie = str =>
  str
  .split(';')
  .map(v => v.split('='))
  .reduce((acc, v) => {
    acc[decodeURIComponent(v[0].trim())] = decodeURIComponent(v[1].trim());
    return acc;
  }, {});

const checkLogin = () => {
  try{
    cookies = parseCookie(document.cookie);
    console.log(cookies['login'])
    if (cookies['login'] == 'connected'){
      searchBar = document.getElementById("recherchebar").style.display = ''
    }
  }
  catch (error){}

  }



  

