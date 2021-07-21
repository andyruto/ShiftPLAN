/**
 * shifts-add.component.ts
 * 
 *  Main typescript class for the shifts-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-28 / Anne Naumann
 */
import { DatePipe, formatDate, Location } from '@angular/common';
import { Component, OnInit, AfterViewInit, AfterViewChecked, HostListener } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { NgxMaterialTimepickerTheme } from 'ngx-material-timepicker';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { GetTasksResponse } from 'src/app/models/gettasksresponse';
import { GetUserDataResponse } from 'src/app/models/getuserdataresponse';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';

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

  tasks: {
    index: number, 
    id: number,
    name: string, 
    timespans: string[]
  }[] = [
    {index: 0, id: 0, name: '', timespans: []}
  ];

  selectedTask = 0
  employeeCount: string[] = ['']
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
  disabled: boolean = true;
  userName: string = '';
  validity: Date = new Date();
  startTime: string = '';
  endTime: string = '';
  contact: string = '';
  comment: string = '';


  position: string = 'absolute';
  originalHeight: number = window.innerHeight;

  timepickerTemp = {button: {name : ''}}

  constructor(
    private translate: TranslateService, 
    public dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService,
    private datepipe: DatePipe,
    private location: Location
    ) { }

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

      this.getTasks();
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
    // this.employeeCount.push('')
    console.log(this.employeeCount)
  }

  public async addShifts() {

    //check inputs
    if(
      // this.validity == '' && 
      this.startTime == '' &&
      this.endTime == '' &&
      this.userName == '' &&
      this.contact == ''
      ) {
      this.dialog.open(ShiftsAddInvalidInputDialog, {
        autoFocus: false
      });

      return
    }

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'ShiftsAdd_spinnerAdd',
      autoFocus: false,
      disableClose: true
    });

    //get user IDs
    //variables
    let publicKeyAnswer;
    let getUserDataAnswer;
    let getSuperAnswer;
    let publicKeyPromise;
    let getUserDataPromise;
    let getSuperPromise;

    let publicKeyErrorCode: number;
    let getUserDataErrorCode: number;
    let getSuperErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;
    var count:number;

    //get public key
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

    //get userdata from api
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    getUserDataAnswer = await this.api.sendPostRequest<GetUserDataResponse>(
      'users/get/', {
        apiKey: apiKey,
        session: sessionAsync,
        filter: 'name',
        value: this.userName
      }
    );
    getUserDataPromise = await getUserDataAnswer.toPromise();
    getUserDataErrorCode = getUserDataPromise.errorCode;

    //get supervisor data from api
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    getSuperAnswer = await this.api.sendPostRequest<GetUserDataResponse>(
      'users/get/', {
        apiKey: apiKey,
        session: sessionAsync,
        filter: 'name',
        value: this.contact
      }
    );
    getSuperPromise = await getSuperAnswer.toPromise();
    getSuperErrorCode = getSuperPromise.errorCode;

    //send add request
    //variables
    let addShiftAnswer;
    let addShiftPromise;
    let addShiftErrorCode: number;

    //prepare times
    let dateString: string = this.datepipe.transform(this.validity, 'yyyy-MM-dd ') as string;
    let shiftStart: string = dateString + this.startTime + ':00';
    let shiftEnd: string = dateString + this.endTime + ':00';

    //send add shift request
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    addShiftAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'shifts/create/', {
        apiKey: apiKey,
        session: sessionAsync,
        assignedUser: getUserDataPromise.profiles[0].id,
        supervisorUser: getSuperPromise.profiles[0].id,
        connectedTaskId: this.tasks.find(x => x.index === this.selectedTask)?.id,
        shiftStart: shiftStart,
        shiftEnd: shiftEnd,
        comment: this.comment
      }
    );
    addShiftPromise = await addShiftAnswer.toPromise();
    addShiftErrorCode = addShiftPromise.errorCode;

    //close spinner
    this.dialog.getDialogById('ShiftsAdd_spinnerAdd')?.close();

    this.location.back();
    
  }

  private async getTasks() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'ShiftsAdd_spinnerAdd',
      autoFocus: false,
      disableClose: true
    });

    //variables
    let publicKeyAnswer;
    let getTasksAnswer;
    let publicKeyPromise;
    let getTasksPromise;

    let publicKeyErrorCode: number;
    let getTasksErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;
    var count: number;
    var indexCounter: number = 0;

    //get public key
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

    //get tasks from api
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    getTasksAnswer = await this.api.sendPostRequest<GetTasksResponse>(
      'tasks/get/', {
        apiKey: apiKey,
        session: sessionAsync
      }
    );
    getTasksPromise = await getTasksAnswer.toPromise();
    getTasksErrorCode = getTasksPromise.errorCode;

    //add tasks to UI
    this.tasks = [];
    let size: number = getTasksPromise.tasks.length;
    if(size == 0) {
      this.tasks.push(...[{index: 0, id: 0, name: '', timespans: []}]);
    }
    getTasksPromise.tasks.forEach(async (element) => {
      var timespans: string[] = [];
      if(element.recurring) {
        //TODO: for recurring tasks
      };

      this.tasks.push(...[{index: indexCounter, id: element.id, name: element.name, timespans: timespans}]);
      indexCounter++;
    });

    //close spinner
    this.dialog.getDialogById('ShiftsAdd_spinnerAdd')?.close();
  }

  public async presentCheck(index: number) {
    if(this.tasks.find(x => x.index === index)?.timespans == []) {
      this.disabled = true;
    }else {
      this.disabled = false;
    }
  }

  private delay(ms: number) {
    return new Promise( resolve => setTimeout(resolve, ms) );
  }

  public setStartTime(event: string) {
    this.startTime = event;
  }

  public setEndTime(event: string) {
    this.endTime = event;
  }

  public setDate(event: any) {
    this.validity = event.target.value;
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