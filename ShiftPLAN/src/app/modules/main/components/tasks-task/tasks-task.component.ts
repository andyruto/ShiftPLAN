/**
 * tasks-input.component.ts
 * 
 *  Main typescript class for the tasks-task component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-07-20 / Anne Naumann
 */
import { Location } from '@angular/common';
import { ThisReceiver } from '@angular/compiler';
import { Component, OnInit, HostListener } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { GetTaskResponse } from 'src/app/models/gettaskresponse';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';

@Component({
  selector: 'app-tasks-task',
  templateUrl: './tasks-task.component.html',
  styleUrls: ['./tasks-task.component.scss']
})
export class TasksTaskComponent implements OnInit {

  title = ''
  editBtn = ''
  labelDescription = ''
  checkBoxOnce = ''
  labelHint = ''
  labelEdit = ''
  labelEditPerson = ''
  labelEditTime = ''
  repeating: string = '';
  id: {id: number, type: string, navigationId: number} = window.history.state;
  description: string = '';
  originalHeight: number = window.innerHeight;
  hidden:boolean = false;

  //boolean that decides which html content is rendered
  oneTimeTask = true
  viewLoaded = true
  taskName = ''

  constructor(
    private translate: TranslateService, 
    public dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService,
    private location: Location
    ) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'TasksTask_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.checkIfRepeating();
    this.fill();

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('TasksTask_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.AdminAdd;
      this.labelDescription = translation.Tasks.Add.LabelDescription;
      this.checkBoxOnce = translation.Tasks.Add.CheckBoxOnce;
      this.labelHint = translation.Tasks.Add.LabelHint;
      this.labelEdit = translation.Tasks.Task.LabelEdit;
      this.labelEditPerson = translation.Tasks.Task.LabelEditPerson;
      this.labelEditTime = translation.Tasks.Task.LabelEditTime;
      this.editBtn = translation.EditButton;
      this.repeating = translation.Tasks.Repeating;
    });
  }

  ngAfterViewChecked(): void{
    let btn = document.getElementById('editTaskBtn')
    if(this.oneTimeTask == false){
      if(btn!.clientHeight != 0 && this.viewLoaded == false){
        let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
        let nameInput = document.getElementsByTagName('mat-form-field')[0].clientHeight
        let buttonContainer = document.getElementById('buttonContainer')?.clientHeight
        var inputContainer = document.getElementById('inputContainer')
    
        let screenHeight = window.innerHeight-toolbar-nameInput-buttonContainer!
        inputContainer!.style.height = screenHeight + 'px'

        this.viewLoaded = true
      } 
    }
  }

  ngOnDestroy() {
    this.dialog.closeAll();
  }

  @HostListener('window:resize', ['$event'])
  setBtnPosition() {
    let currentHeight: number = window.innerHeight;
    if(currentHeight < this.originalHeight) {
      this.hidden = true;
    }else {
      this.hidden = false;
    }
  }

  private checkIfRepeating() {
    switch(this.id.type) {
      case this.repeating: {
        this.oneTimeTask = false;
        this.viewLoaded = false;
        break;
      }
      default: {
        this.oneTimeTask = true;
        this.viewLoaded = true;
        break;
      }
    }
  }

  private async fill() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'TasksTask_spinnerFill',
      autoFocus: false,
      disableClose: true
    });

    //variables
    let publicKeyAnswer;
    let getTaskAnswer;
    let publicKeyPromise;
    let getTaskPromise;

    let publicKeyErrorCode: number;
    let getTaskErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;

    //get public key
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

    //get task from api
    getTaskAnswer = await this.api.sendPostRequest<GetTaskResponse>(
      'tasks/get/', {
        apiKey: apiKey,
        session: sessionAsync,
        id: this.id.id
      }
    );
    getTaskPromise = await getTaskAnswer.toPromise();
    //DEBUG
    console.log(getTaskPromise.task)
    getTaskErrorCode = getTaskPromise.errorCode;

    //fill in UI
    this.description = getTaskPromise.task[0].name;

    //close spinner
    this.dialog.getDialogById('TasksTask_spinnerFill')?.close();
  }

  public async modifyTask(){

    //check inputs
    if(this.description == '') {
      this.dialog.open(TasksTaskInvalidInputDialog, {
        autoFocus: false
      })
      return
    }

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'TaskTask_spinnerModify',
      autoFocus: false,
      disableClose: true
    });

    //variables
    let publicKeyAnswer;
    let modifyAnswer;
    let publicKeyPromise;
    let modifyPromise;

    let publicKeyErrorCode: number;
    let modifyErrorCode: number;
    let publicKey: string;
    let sessionAsync: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;
    let session: string = localStorage.getItem('Session') as string;

    //get public key
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

    //DEBUG
    console.log(apiKey)
    console.log(sessionAsync)
    console.log(this.id.id)
    console.log(this.description)

    //modify task
    modifyAnswer = await this.api.sendPostRequest<GeneralResponse>(
      'tasks/modify/', {
        apiKey: apiKey,
        session: sessionAsync,
        id: this.id.id,
        name: this.description
      }
    );
    modifyPromise = await modifyAnswer.toPromise();
    //DEBUG
    console.log(modifyPromise)
    modifyErrorCode = modifyPromise.errorCode;

    //close spinner
    this.dialog.getDialogById('TaskTask_spinnerModify')?.close();

    this.location.back();
  }
}

@Component({
  selector: 'invalid-data-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./tasks-task.component.scss'],
})
export class TasksTaskInvalidInputDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<TasksTaskInvalidInputDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'TasksTask_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('TasksTask_spinner')?.close();

    this.warning = translation.Tasks.Task.InputWarning;
    this.ok = translation.Tasks.Task.Ok;
    });
  }
}