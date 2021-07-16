import myComponent from "./myComponent";
import "../resources/style/table.scss";
import {createRef} from "react";

class ArchiveBody extends myComponent {
    self = this;
    constructor(props) {
        super(props);
        this.state = {
            'mails':this.passAsValue(props.mails),
            'sort':{
                'colNo':this.passAsValue(props.firstSort).colNo,
                'dir':this.passAsValue(props.firstSort).dir
            }
        }
        this.bg_logo = createRef();
        this.hover_info = createRef();
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if(this.props.mails.length === 0) this.bg_logo.current.style.display = "block";
        else this.bg_logo.current.style.display = "none";
    }

    sortBy(colIndex) {
        let newDir = 'D';
        if(this.state.sort.colNo !== colIndex) {
            for(let i = 0; i < 2; i++){
            let prev_target = document.getElementsByClassName('mails-table')[i]
                .getElementsByClassName('sorted-' + this.state.sort.dir)[0];
            if (prev_target !== undefined) prev_target.className = '';
            }
            this.setState({
                'sort':{
                    'colNo':colIndex,
                    'dir':newDir
                }
            }, ()=>this.props.sortCallBack(this.state.sort));
        } else {
            if(this.state.sort.dir === 'D') newDir = 'A';
            this.setState({
                'sort':{
                    'colNo':colIndex,
                    'dir':newDir
                }
            }, ()=>this.props.sortCallBack(this.state.sort));
        }
        let new_target = document.getElementsByClassName('mails-table')[0]
            .getElementsByTagName('tr')[0].getElementsByTagName('th')[colIndex];
        new_target.className = 'sorted-' + newDir;
        new_target = document.getElementsByClassName('mails-table')[1]
            .getElementsByTagName('tr')[0].getElementsByTagName('span')[colIndex];
        new_target.className = 'sorted-' + newDir;
    }

    beginHover(e, id) {
        if (!this.props.hoverActive) return;
        let hoverBox = this.hover_info.current;
        hoverBox.className='mail-'+id;
        hoverBox.style.display = "block";
        hoverBox.style.top = -50 + e.clientY + "px";
        hoverBox.style.left = 50 + e.clientX + "px";
        let targetMail;
        for (let i = 0; i < this.state.mails.length; i++) {
            if (this.state.mails[i].id === id){
                targetMail = this.passAsValue(this.state.mails[i]);
                break;
            }
        }
        let to_links = "";
        for (let i = 0; i < targetMail.to.length; i++) {
            to_links += "<a href='mailto:"+targetMail.to[i]+"'>"+targetMail.to[i]+"</a>";
            if (i !== targetMail.to.length - 1) to_links += ", ";
        }
        hoverBox.getElementsByClassName('content')[0].getElementsByClassName('from')[0]
            .getElementsByTagName('span')[1].innerHTML = "<a href='mailto:"+targetMail.from+"'>"+targetMail.from+"</a>";
        hoverBox.getElementsByClassName('content')[0].getElementsByClassName('to')[0]
            .getElementsByTagName('span')[1].innerHTML = to_links;
        hoverBox.getElementsByClassName('content')[0].getElementsByClassName('subject')[0]
            .getElementsByTagName('span')[1].innerHTML = targetMail.subject;
        hoverBox.getElementsByClassName('content')[0].getElementsByClassName('body')[0]
            .getElementsByTagName('span')[0].innerHTML = targetMail.date;
        hoverBox.getElementsByClassName('content')[0].getElementsByClassName('body')[0]
            .getElementsByTagName('span')[1].innerHTML = targetMail.body;
        let files = targetMail.files;
        let files_txt = "";
        for(let i = 0; i < files.length; i++) {
            files_txt += '<a href='+files[i].url+'><span>'+files[i].name+'</span></a>';
        }
        if (files_txt.length === 0) files_txt = '<span>No Files Attached</span>';
        hoverBox.getElementsByClassName('content')[0].getElementsByClassName('files')[0]
            .getElementsByTagName('span')[1].innerHTML = files_txt;
    }

    endHover() {
        let hoverBox = this.hover_info.current;
        hoverBox.className='';
        hoverBox.style.display = "none";
    }

    render() {
        return(
            <main id="a-body">
                <div ref={this.bg_logo} id="bg-logo"></div>
                <table className="mails-table desktop">
                    <thead>
                        <tr>
                            <th onClick={()=>this.sortBy(0)}>
                                From
                            </th>
                            <th onClick={()=>this.sortBy(1)}>
                                To
                            </th>
                            <th onClick={()=>this.sortBy(2)}>
                                Subject
                            </th>
                            <th className="sorted-D" onClick={()=>this.sortBy(3)}>
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody onMouseLeave={()=>this.endHover()}>
                    {

                        this.props.mails.map((mail)=>{
                            return(
                                <tr data-mail-id={mail.id} onMouseEnter={(event)=>this.beginHover(event, mail.id)}>
                                    <td>
                                        <div>
                                            <a href={"mailto:"+mail.from}>{mail.from}</a>
                                        </div>
                                    </td>
                                    <td className={((mail.to.length > 1) ? ("additional-contacts") : (""))}
                                        data-additional-contact={((mail.to.length > 1) ? ("+"+(mail.to.length-1)) : (""))}>
                                        <div>
                                            <a href={"mailto:"+mail.to[0]}>{mail.to[0]}</a>{((mail.to.length > 1) ? (", ...") : (""))}
                                        </div>
                                    </td>
                                    <td className={((mail.files.length > 0) ? ('attached') : (''))}>
                                        <div>
                                        {mail.subject}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                        {mail.date}
                                        </div>
                                    </td>
                                </tr>
                            )
                        })
                    }
                    <div ref={this.hover_info} id="moreinfo-hover">
                        <div className="content grid">
                            <div className="from grid"><span>From</span><span></span></div>
                            <div className="to grid"><span>To</span><span></span></div>
                            <div className="subject grid"><span>Subject</span><span></span></div>
                            <div className="body"><div>Content<span></span></div><span></span></div>
                            <div className="files grid"><span>Files</span><span></span></div>
                        </div>
                    </div>
                    </tbody>
                </table>
                <table className="mails-table mobile">
                    <thead>
                    <tr>
                        <th>
                            <span onClick={()=>this.sortBy(0)}>
                                From
                            </span>
                            <span onClick={()=>this.sortBy(1)}>
                                To
                            </span>
                            <span onClick={()=>this.sortBy(2)}>
                                Subject
                            </span>
                            <span className="sorted-D" onClick={()=>this.sortBy(3)}>
                                Date
                            </span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    {

                        this.props.mails.map((mail)=>{
                            let add_contact = ((mail.to.length === 1) ? ("") : (<a href={"mailto:"+mail.to[1]}>{mail.to[1]}</a>));
                            return(
                                <tr data-mail-id={mail.id} onMouseEnter={(event)=>this.beginHover(event, mail.id)}>
                                    <td className="grid">
                                        <div className="from">
                                            <a href={"mailto:"+mail.from}><b>{mail.from}</b></a>
                                        </div>
                                        <div className={((mail.to.length > 2) ? ("to additional-contacts") : ("to"))}
                                             data-additional-contact={((mail.to.length > 2) ? ("+"+(mail.to.length-2)) : (""))}>
                                            <a href={"mailto:"+mail.to[0]}>{mail.to[0]}</a>
                                            {((mail.to.length > 1) ? (", ") : (""))}
                                            {add_contact}
                                            {((mail.to.length > 2) ? (", ...") : (""))}
                                        </div>
                                        <div className="subject">
                                            {mail.subject}
                                        </div>
                                        <div className={((mail.files.length > 0) ? ('date attached') : ('date'))}>
                                            {mail.date}
                                        </div>
                                    </td>
                                </tr>
                            )
                        })
                    }
                    </tbody>
                </table>
            </main>
        )
    }
}
export default ArchiveBody;