/**
 * shifts-add.component.ts
 * 
 *  Main typescript class for the shifts-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-28 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit, AfterViewChecked, HostListener } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { NgxMaterialTimepickerTheme } from 'ngx-material-timepicker';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-shifts-add',
  templateUrl: './shifts-add.component.html',
  styleUrls: ['./shifts-add.component.scss']
})
export class ShiftsAddComponent implements OnInit, AfterViewInit, AfterViewChecked {

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

  tasks: {index: number, name: string, timespans: string[]}[] = [
    {index: 0, name: 'Task1', timespans: ['Mittwoch, 10:00 - 13:00Uhr', 'Samstag, 10:00 - 13:00Uhr']},
    {index: 1, name: 'Task2', timespans: ['Dienstag, 10:00 - 13:00Uhr', 'Freitag, 10:00 - 13:00Uhr']},
    {index: 2, name: 'Task3', timespans: []}
  ]

  selectedTask = 0
  employeeCount = [1, 2]
  contentLoaded = false

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
  cancelBtn = ''

  position: string = 'absolute';
  originalHeight: number = window.innerHeight;

  timepickerTemp = {button: {name : ''}}

  constructor(private translate: TranslateService, public dialog: MatDialog) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'ShiftsAdd_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('ShiftsAdd_spinnerTranslationGlobal')?.close();

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
      this.cancelBtn = translation.CancelButton;
    });
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let container = document.getElementById('buttonContainer')?.clientHeight
    var scrollView = document.getElementById('shiftAddScrollView')

    let screenHeight = window.innerHeight-toolbar-container!
    scrollView!.style.height = screenHeight + 'px'
  }

  ngAfterViewChecked(): void{
    if(this.cancelBtn != '' && this.contentLoaded == false){
      this.timepickerTemp.button.name = this.cancelBtn
      this.contentLoaded = true
    }
  }

  ngOnDestroy() {
    this.dialog.closeAll();
  }


  @HostListener('window:resize', ['$event'])
  setBtnPosition() {
    let currentHeight: number = window.innerHeight;
    if(currentHeight < this.originalHeight) {
      this.position = 'static';
    }else {
      this.position = 'absolute';
    }
  }

  addEmployee(){
    this.employeeCount.push(1)
    console.log(this.employeeCount)
  }

  private async getTasks() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinnerAdd',
      autoFocus: false,
      disableClose: true
    });

    //TODO
  }
}

@Component({
  selector: 'invalid-data-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./shifts-add.component.scss'],
})
export class ShiftsAddInvalidInputDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<ShiftsAddInvalidInputDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('Tasks_spinner')?.close();

    this.warning = translation.Tasks.Add.InputWarning;
    this.ok = translation.Tasks.Add.Ok;
    });
  }
}