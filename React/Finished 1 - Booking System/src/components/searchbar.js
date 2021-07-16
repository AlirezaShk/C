import myComponent from "./myComponent";
import DatePicker from "react-datepicker"
import "react-datepicker/dist/react-datepicker.css";
import "../resources/style/searchbar.scss";

class Searchbar extends myComponent {
    constructor(props) {
        super(props);
        this.state = {
            'from':new Date(props.firstDate),
            'to':new Date()
        }
    }

    setFrom(date) {
        this.setState({
            from: new Date(date)
        })
    }

    setTo(date) {
        this.setState({
            to: new Date(date)
        })
    }

    beginSearch(){
        this.props.searchCallback(this.state);
    }

    render() {
        return (
            <div id="dateSearch">
                <DatePicker id="fromPicker" selected={this.state.from}
                            onChange={date => this.setFrom(date)}
                            minDate={new Date(this.props.firstDate)}
                            maxDate={this.state.to}
                            dateFormat="yyyy/MM/dd"
                />
                <span className="seperator"> — </span>
                <DatePicker id="toPicker" selected={this.state.to}
                            onChange={date => this.setTo(date)}
                            minDate={this.state.from}
                            maxDate={new Date()}
                            dateFormat="yyyy/MM/dd"
                />
                <span className="search-btn" onClick={()=>this.beginSearch()}></span>
            </div>
        )
    }
}

export default Searchbar;