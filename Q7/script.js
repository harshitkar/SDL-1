alert("Welcome! JavaScript Basics");

const avgLifeSpan = 80;
const weeksInYear = 52;
const lifeinWeeks = avgLifeSpan * weeksInYear;

console.log("An average person lives for " + lifeinWeeks + " weeks.");

let greet = "Hello";
console.log(greet + " World!");

function showTime() {
    let currentHour = new Date().getHours();
    let timeOfDay;

    if( currentHour >= 5 && currentHour < 12 ) {
        timeOfDay = "Morning";
    }
    else if( currentHour >= 12 && currentHour < 18 ) {
        timeOfDay = "Afternoon";
    }
    else if( currentHour >= 18 && currentHour < 22 ) {
        timeOfDay = "Evening";
    }
    else {
        timeOfDay = "Night";
    }
    console.log("Good " + timeOfDay + "!");
}