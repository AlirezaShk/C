import myComponent from "./myComponent";
import ArchiveHeader from './archiveHeader';
import ArchiveBody from './archiveBody';
import {createRef} from "react";
import "../resources/style/project.scss";

class Project extends myComponent {
    constructor(props) {
        super(props);
        this.headerRef = createRef();
        const mailArray = [
            {
                'id': 0,
                'from': 'sample_00@mail.xxx',
                'to': ['sample_01@mail.xxx', 'sample_03@mail.xxx'],
                'subject': 'Sample Subject 00',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[
                    {
                        'name':'Attached Sample 00',
                        'size':'1249',
                        'ext':'pdf',
                        'url':'files/Attached_Sample_00.pdf'
                    }
                ],
                'date': '2019/12/31'
            },
            {
                'id': 1,
                'from': 'sample_02@mail.xxx',
                'to': ['sample_00@mail.xxx'],
                'subject': 'Sample Subject 02',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[],
                'date': '2020/12/31'
            },
            {
                'id': 2,
                'from': 'sample_01@mail.xxx',
                'to': ['sample_03@mail.xxx'],
                'subject': 'Sample Subject 01',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[
                    {
                        'name':'Attached Sample 01',
                        'size':'124929',
                        'ext':'mp3',
                        'url':'files/Attached_Sample_01.mp3'
                    }
                ],
                'date': '2021/04/01'
            },
            {
                'id': 3,
                'from': 'sample_03@mail.xxx',
                'to': ['sample_02@mail.xxx', 'sample_04@mail.xxx', 'sample_05@mail.xxx'],
                'subject': 'Sample Subject 05',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[],
                'date': '2021/05/10'
            },
            {
                'id': 4,
                'from': 'sample_04@mail.xxx',
                'to': ['sample_01@mail.xxx'],
                'subject': 'Sample Subject 04',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[],
                'date': '2020/05/22'
            },
            {
                'id': 5,
                'from': 'sample_01@mail.xxx',
                'to': ['sample_04@mail.xxx'],
                'subject': 'Sample Subject 03',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[],
                'date': '2020/12/31'
            },
            {
                'id': 6,
                'from': 'sample_01@mail.xxx',
                'to': ['sample_03@mail.xxx'],
                'subject': 'Sample Subject 01',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[],
                'date': '2021/02/17'
            },
            {
                'id': 7,
                'from': 'sample_03@mail.xxx',
                'to': ['sample_02@mail.xxx', 'sample_00@mail.xxx', 'sample_04@mail.xxx'],
                'subject': 'Sample Subject 05',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[],
                'date': '2020/08/19'
            },
            {
                'id': 8,
                'from': 'sample_01@mail.xxx',
                'to': ['sample_03@mail.xxx'],
                'subject': 'Sample Subject 06',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[],
                'date': '2021/05/12  01:04:01'
            },
            {
                'id': 9,
                'from': 'sample_03@mail.xxx',
                'to': ['sample_02@mail.xxx', 'sample_01@mail.xxx'],
                'subject': 'Sample Subject 08',
                'body':'ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ ABCDEFGHIJKLMNOPQRSTUVWXXYZ',
                'files':[
                    {
                        'name':'Attached Sample 02',
                        'size':'482934',
                        'ext':'mkv',
                        'url':'files/Attached_Sample_02.mkv'
                    }
                ],
                'date': '2021/05/12  01:11:00'
            }
        ];
        this.state = {
            'mails':this.passAsValue(mailArray),
            'currentMails':this.passAsValue(mailArray),
            'currentMailsFormated':this.mailArrayFormatDate(mailArray),
            'currentSort':{
                'colNo':3,
                'dir':'D'
            },
            'hoverActive': true
        }
    }

    searchCallBack(date) {
        let mails = this.passAsValue(this.state.mails);
        let result = [];
        mails.map((mail)=>{
            let d = new Date(mail.date);
            if((d <= date.to) && (d >= date.from)) result.push(mail);
        });
        this.sortMails(this.state.currentSort, result);
        this.setState({
            'currentMails':this.passAsValue(result),
            'currentMailsFormated':this.mailArrayFormatDate(this.passAsValue(result))
        });
    }

    mailArrayFormatDate(mails) {
        let mailArrayFormated = [];
        for(let i = 0; i < mails.length; i++) {
            mailArrayFormated[i] = this.passAsValue(mails[i]);
            mailArrayFormated[i].date = this.formatDate(mails[i].date);
        }
        return mailArrayFormated;
    }

    mailArrayDEFormatDate(mailsFormated) {
        let mailArray = [];
        console.log(mailsFormated);
        console.log(this.state.mails);
        for(let i = 0; i < mailsFormated.length; i++) {
            for(let j = 0; j < this.state.mails.length; j++) {
                if (mailsFormated[i].id === this.state.mails[j].id) {
                    mailArray.push(this.passAsValue(this.state.mails[j]));
                }
            }
        }
        return mailArray;
    }

    sortMails(sort, mails = undefined) {
        mails = ((mails === undefined) ? (this.state.currentMails) : (this.mailArrayDEFormatDate(mails)));
        mails.sort((a,b)=>{
            let k = 1, t = 1;
            if (sort.dir === "A") k = -1;
            switch(sort.colNo) {
               case 0:
                   if(a.from > b.from) t = -1 * k;
                   else if(a.from < b.from) t = 1 * k;
                   else t = 0;
                   break;
               case 1:
                   if(a.to > b.to) t = -1 * k;
                   else if(a.to < b.to) t = 1 * k;
                   else t = 0;
                   break;
               case 2:
                   if(a.subject > b.subject) t = -1 * k;
                   else if(a.subject < b.subject) t = 1 * k;
                   else t = 0;
                   break;
               case 3:
                   if(a.date > b.date) t = -1 * k;
                   else if(a.date < b.date) t = 1 * k;
                   else t = 0;
                   break;
            }
            return t;
        });
        this.setState({
            'currentMails': mails,
            'currentMailsFormated':this.mailArrayFormatDate(this.passAsValue(mails)),
            'currentSort': sort
        });
    }

    componentDidMount() {
        this.sortMails(this.state.currentSort);
    }

    formatDate(date) {
        let today =  new Date();
        date = new Date(date);
        let diff = (today.getTime() - date.getTime())/1000; /* convert ms to s */
        let monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
        if (diff > 3600*24 && diff <= 3600*24*31) return monthNames[date.getMonth()] + " " + this.format2DigitDate(date.getDate());
        else if (diff <= 3600*24) {
            return this.format2DigitDate(date.getHours()) + ":" + this.format2DigitDate(date.getMinutes());
        }
        else return date.getFullYear() + "/" + this.format2DigitDate(date.getMonth()+1) + "/" + this.format2DigitDate(date.getDate());
    }

    hoverCallBack(state) {
        this.setState({
            'hoverActive': state
        });
    }

    render() {
        return(
            <div id="project">
                <ArchiveHeader hoverActive={this.state.hoverActive} hoverCallBack={this.hoverCallBack.bind(this)} mailCount={this.state.currentMails.length} searchCallBack={this.searchCallBack.bind(this)} firstDate={this.state.mails[0].date} ref={this.headerRef}></ArchiveHeader>
                <ArchiveBody hoverActive={this.state.hoverActive} sortCallBack={this.sortMails.bind(this)} mails={this.state.currentMailsFormated} firstSort={this.state.currentSort}></ArchiveBody>
            </div>
        )
    }
}

export default Project;