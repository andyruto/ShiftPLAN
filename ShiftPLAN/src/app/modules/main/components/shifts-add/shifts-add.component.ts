/**
 * shifts-add.component.ts
 * 
 *  Main typescript class for the shifts-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-27 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NgxMaterialTimepickerTheme } from 'ngx-material-timepicker';

@Component({
  selector: 'app-shifts-add',
  templateUrl: './shifts-add.component.html',
  styleUrls: ['./shifts-add.component.scss']
})
export class ShiftsAddComponent implements OnInit, AfterViewInit {

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

  tasks = [
    {index: 0, name: 'Task1', timespans: ['Mittwoch, 10:00 - 13:00Uhr', 'Samstag, 10:00 - 13:00Uhr']},
    {index: 1, name: 'Task2', timespans: ['Dienstag, 10:00 - 13:00Uhr', 'Freitag, 10:00 - 13:00Uhr']},
    {index: 2, name: 'Task3', timespans: []}
  ]

  selectedTask = 0
  employeeCount = [1, 2]

  title = ''
  labelTask = ''
  labelTimespan = ''
  labelValidity = ''
  labelStart = ''
  labelEnd = ''
  labelEmployee = ''
  labelContact = ''
  labelRemark = ''
  saveBtn = ''

  constructor(private translate: TranslateService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.ShiftsAdd;
      this.labelTask = translation.Shifts.LabelTask;
      this.labelTimespan = translation.Shifts.LabelTimespan;
      this.labelValidity = translation.Shifts.LabelValidity;
      this.labelStart = translation.Shifts.LabelStart;
      this.labelEnd = translation.Shifts.LabelEnd;
      this.labelEmployee = translation.Shifts.LabelEmployee;
      this.labelContact = translation.Shifts.LabelContact;
      this.labelRemark = translation.Shifts.LabelRemark;
      this.saveBtn = translation.SaveButton;
    });
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let container = document.getElementById('buttonContainer')?.clientHeight
    var scrollView = document.getElementById('shiftAddScrollView')

    let screenHeight = window.innerHeight-toolbar-container!
    scrollView!.style.height = screenHeight + 'px'
  }

  addEmployee(){
    this.employeeCount.push(1)
    console.log(this.employeeCount)
  }
}
