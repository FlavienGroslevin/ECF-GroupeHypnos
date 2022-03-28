$(document).ready(function (){
    const start = datepicker('.start', {
        id:1,
        formatter :(input, date) => {
            input.value = date.toLocaleDateString()
        },
        startDay : 1,
        customDays : ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        customMonths : ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        overlayButton : 'Valider',
        overlayPlaceholder : 'Entrez une année',
        minDate: new Date(Date.now()),
    });
    const end = datepicker('.end', {
        id:1,
        formatter :(input, date) => {
            input.value = date.toLocaleDateString()
        },
        startDay: 1,
        customDays : ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        customMonths : ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        overlayButton : 'Valider',
        overlayPlaceholder : 'Entrez une année',
        minDate: new Date(Date.now()),
    });
})