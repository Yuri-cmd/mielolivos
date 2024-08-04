function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function formatDate(fechaOriginal) { 
    // Crear un objeto Date
    const fecha = new Date(fechaOriginal);

    // Obtener el nombre del día en español
    const opcionesDia = { weekday: 'long' };
    const nombreDia = fecha.toLocaleDateString('es-ES', opcionesDia);

    // Obtener el día del mes
    const diaMes = fecha.getDate();

    // Obtener el nombre del mes en español
    const opcionesMes = { month: 'long' };
    const nombreMes = fecha.toLocaleDateString('es-ES', opcionesMes);

    // Formatear la fecha
    return fechaFormateada = `${nombreDia.charAt(0).toUpperCase() + nombreDia.slice(1)} ${diaMes} ${nombreMes}`;

}

function formatNumber(param) { 
    return parseFloat(param).toFixed(2);

}

function formatDate(fechaOriginal) { 
    // Crear un objeto Date
    const fecha = new Date(fechaOriginal);

    // Obtener el nombre del día en español
    const opcionesDia = { weekday: 'long' };
    const nombreDia = fecha.toLocaleDateString('es-ES', opcionesDia);

    // Obtener el día del mes
    const diaMes = fecha.getDate();

    // Obtener el nombre del mes en español
    const opcionesMes = { month: 'long' };
    const nombreMes = fecha.toLocaleDateString('es-ES', opcionesMes);

    // Formatear la fecha
    return fechaFormateada = `${nombreDia.charAt(0).toUpperCase() + nombreDia.slice(1)} ${diaMes} ${nombreMes}`;

}