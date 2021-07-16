import { Component } from 'react';

class myComponent extends Component {
    format2DigitDate(num){
        return (
            (num < 10) ? ('0'+num) : (num)
        )
    }

    passAsValue(obj) { return JSON.parse(JSON.stringify(obj)); }
    // formatFullDate(date) {
    //     return date.getFullYear() + "/"
    //         + this.format2DigitDate(date.getMonth()+1) + "/"
    //         + this.format2DigitDate(date.getDate());
    // }
}
export default myComponent;