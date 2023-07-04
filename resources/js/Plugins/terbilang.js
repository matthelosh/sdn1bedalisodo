const huruf = [
    "",
    "Satu",
    "Dua",
    "Tiga",
    "Empat",
    "Lima",
    "Enam",
    "Tujuh",
    "Delapan",
    "Sembilan",
    "Sepuluh",
    "Sebelas"
]

export const terbilang = (number) => {
    const sign = Math.sign(number)
    number = Math.abs(number)
    let nilaiBagi = 0, temp = "";

    if (number < 12) {
        temp = ""+huruf[number]
    } else if(number < 20) {
        temp = terbilang(number - 10) + " Belas"
    } else if(number < 100) {
        nilaiBagi = Math.floor(number/10);
        temp = terbilang(nilaiBagi)+" Puluh "+terbilang(number % 10);
    } else if (number < 200) {
        temp = "Seratus " + terbilang(number - 100)
    } else if (number < 1000) {
        nilaiBagi = Math.floor(number/100);
        temp = terbilang(nilaiBagi)+" Ratus " + terbilang(number % 100);
    } else if (number < 2000) {
        temp = "Seribu "+ terbilang(number - 1000);
    } else if (number < 1000000) {
        nilaiBagi = Math.floor(number / 1000)
        temp = terbilang(nilaiBagi) + " Ribu " + terbilang(number % 1000)
    } else if (number < 1000000000) {
        nilaiBagi = Math.floor(number / 1000000)
        temp = terbilang(nilaiBagi) + " Juta " + terbilang(number % 1000000)
    } else if (number < 1000000000000) {
        nilaiBagi = Math.floor(number / 1000000000)
        temp = terbilang(nilaiBagi) + " Miliar " + terbilang(number % 1000000000)
    } else if (number < 1_000_000_000_000_000) {
        nilaiBagi = Math.floor(number / 1000000000000)
        temp = terbilang(nilaiBagi) + " Triliun " + terbilang(number % 1000000000000)
    }
    

    return  (sign == "-1" ? "Minus ": "") + temp;
}



export default { terbilang }