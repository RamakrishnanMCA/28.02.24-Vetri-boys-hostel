function downloadReportPDF(){
    const element= document.getElementById("invoice");
    html2pdf()
    .from(element)
    .save();
    
}