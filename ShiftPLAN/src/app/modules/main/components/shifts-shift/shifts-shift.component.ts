/**
 * shifts-shift.component.ts
 * 
 *  Main typescript class for the shifts-shift component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-27 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit, AfterViewChecked, HostListener } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { NgxMaterialTimepickerTheme } from 'ngx-material-timepicker';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-shifts-shift',
  templateUrl: './shifts-shift.component.html',
  styleUrls: ['./shifts-shift.component.scss']
})
export class ShiftsShiftComponent implements OnInit, AfterViewInit, AfterViewChecked {

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
    date: new Date('2021-07-05'), start: '08:00', end: '14:00', task: 'Empfang_01', contact: 'ag167', 
    remark: 'Bitte auf die neue Beschränkungen achten.', employees: ['ag167', 'an055', 'sw210']
  }

  comments = [
    {user: 'sw210', time: '30.06.21, 16:29 Uhr', text: 'Wo kann ich das neue Dokument finden?'}
  ]

  //boolean to determine if warning is shown
  showWarningChanges = true
  contentChanged = false
  contentLoaded = false

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
  cancelBtn = ''

  
  position: string = 'absolute'
  originalHeight: number = window.innerHeight;

  timepickerTemp = {button: {name : ''}}

  constructor(private translate: TranslateService, public dialog: MatDialog) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'ShiftsShift_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('ShiftsShift_spinnerTranslationGlobal')?.close();

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
      this.cancelBtn = translation.CancelButton;
    });
  }

  ngAfterViewInit(): void{
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let textContainer = document.getElementById('textContainer')?.clientHeight
    var scrollView = document.getElementById('shiftScrollView')

    let screenHeight = window.innerHeight-toolbar-textContainer!
    scrollView!.style.height = screenHeight + 'px'
  }

  ngAfterViewChecked(): void{
    if(this.cancelBtn != '' && this.contentLoaded == false){
      this.timepickerTemp.button.name = this.cancelBtn
      this.contentLoaded = true
    }
  }

  //resizing on size change (active keyboard)
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
    this.shift.employees.push('')
  }

  contentChange(){
    this.contentChanged = true
  }
}

@Component({
  selector: 'invalid-data-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./shifts-shift.component.scss'],
})
export class ShiftsShiftInvalidInputDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<ShiftsShiftInvalidInputDialog>, private translation: TranslateService, public dialog : MatDialog) {}

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