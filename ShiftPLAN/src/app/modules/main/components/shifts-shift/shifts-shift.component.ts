/**
 * shifts-shift.component.ts
 * 
 *  Main typescript class for the shifts-shift component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-27 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NgxMaterialTimepickerTheme } from 'ngx-material-timepicker';

@Component({
  selector: 'app-shifts-shift',
  templateUrl: './shifts-shift.component.html',
  styleUrls: ['./shifts-shift.component.scss']
})
export class ShiftsShiftComponent implements OnInit, AfterViewInit {

  timePickerTheme: NgxMaterialTimepickerTheme = {
    container: {
        bodyBackgroundColor: 'white',
        buttonColor: '#6a9be6'
    },
    dial: {
        dialBackgroundColor: '#6a9be6',
    },
    clockFace: {
        clockFaceBackgroundColor: '#F6F6F6',
        clockHandColor: '#e6a21a',
        clockFaceTimeInactiveColor: 'black'
    }
  };

  shift = {
    date: new Date('2021-05-18'), start: '10:00', end: '13:00', task: 'Task1', contact: 'Andreas Gantner', 
    remark: 'Anmerkungen sind wichtig', employees: ['Anne Naumann', 'Sascha Wirtz', 'Anne Naumann']
  }

  comments = [
    {user: 'admin', time: '27.06.21, 16:29 Uhr', text: 'Ein Kommentar zu dieser Schicht'},
    {user: 'user', time: '27.06.21, 16:30 Uhr', text: 'Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text'}
  ]

  //boolean to determine if warning is shown
  showWarningChanges = true
  contentChanged = false

  title = ''
  labelWarning = ''
  labelTask = ''
  labelTimespan = ''
  labelValidity = ''
  labelStart = ''
  labelEnd = ''
  labelEmployee = ''
  labelContact = ''
  labelRemark = ''
  labelComments = ''
  editBtn = ''

  constructor(private translate: TranslateService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.ShiftsShift;
      this.labelWarning = translation.Shifts.LabelWarning;
      this.labelTask = translation.Shifts.LabelTask;
      this.labelTimespan = translation.Shifts.LabelTimespan;
      this.labelValidity = translation.Shifts.LabelValidity;
      this.labelStart = translation.Shifts.LabelStart;
      this.labelEnd = translation.Shifts.LabelEnd;
      this.labelEmployee = translation.Shifts.LabelEmployee;
      this.labelContact = translation.Shifts.LabelContact;
      this.labelRemark = translation.Shifts.LabelRemark;
      this.labelComments = translation.Shifts.LabelComments;
      this.editBtn = translation.EditButton;
    });
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let textContainer = document.getElementById('textContainer')?.clientHeight
    var scrollView = document.getElementById('shiftScrollView')

    let screenHeight = window.innerHeight-toolbar-textContainer!
    scrollView!.style.height = screenHeight + 'px'
  }

  addEmployee(){
    this.shift.employees.push('')
  }

  contentChange(){
    this.contentChanged = true
  }
}
