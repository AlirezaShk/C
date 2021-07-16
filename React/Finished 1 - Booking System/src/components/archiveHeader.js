import myComponent from "./myComponent";
import Searchbar from './searchbar';
import {createRef} from "react";

class ArchiveHeader extends myComponent {
    constructor(props) {
        super(props);
        this.searchRef = createRef();
        this.hoverRef = createRef();

        this.state = {
            'test':1
        }
        this.toggleHover = this.toggleHover.bind(this);
    }

    toggleHover() {
        this.props.hoverCallBack(((this.props.hoverActive) ? (false) : (true)));
    }

    render(){
        return(
            <header id="a-header">
                <Searchbar searchCallback={this.props.searchCallBack} firstDate={this.props.firstDate} ref={this.searchRef}></Searchbar>
                <div className="result-num">Results: {this.props.mailCount} mail(s)</div>
                <div className="hover-toggle"><button ref={this.hoverRef} className={((this.props.hoverActive) ? ('toggle-on') : ('toggle-off'))} onClick={this.toggleHover}>Hover {((this.props.hoverActive) ? ('Active') : ('Inactive'))}</button></div>
            </header>
        )
    }
}

export default ArchiveHeader;