/**
 * tasks.component.ts
 * 
 *  Main typescript class for the tasks component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-12 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit, HostListener } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Router } from '@angular/router'
import { UsertypeService } from 'src/app/services/usertype.service';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { MatDialog } from '@angular/material/dialog';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { GetTasksResponse } from 'src/app/models/gettasksresponse';

@Component({
  selector: 'app-tasks',
  templateUrl: './tasks.component.html',
  styleUrls: ['./tasks.component.scss']
})
export class TasksComponent implements OnInit, AfterViewInit {

  title = ''
  searchBarText = ''
  admin: boolean = false
  repeating: string = ''
  unique: string = ''

  //test content that needs to be replaced
  tasks!: {id: number, name: string, type: string}[];

  constructor(
    private translate: TranslateService, 
    private router : Router,
    private usertype : UsertypeService, 
    public dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService
    ) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });
      
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('Tasks_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.Tasks;
      this.searchBarText = translation.Tasks.SearchBarText;
      this.repeating = translation.Tasks.Repeating;
      this.unique = translation.Tasks.Unique;

      this.refreshTasks();
    });

    this.checkBtn();
  }

  ngAfterViewInit(): void{
    this.setScreenSize();
  }

  @HostListener('window:resize', ['$event'])
  setScreenSize() {
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0]
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0]
    let searchBar = document.getElementsByTagName('mat-form-field')[0]
    var scrollView = document.getElementById('tasksScrollView')

    let screenHeight = window.innerHeight-toolbar.clientHeight-bottomBar.clientHeight-searchBar.clientHeight
    scrollView!.style.height = screenHeight + 'px'
  }

  ngOnDestroy() {
    this.dialog.closeAll();
  }

  private async refreshTasks() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinnerRefresh',
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

    //addTasksToUI
    this.tasks = [];
    getTasksPromise.tasks.forEach(element => {
      let type: string;
      switch(element.recurring) {
        case true: {
          type = this.repeating;
          break;
        }
        default: {
          type = this.unique;
          break;
        }
      }
      this.tasks.push(...[{id: element.id, name: element.name, type: type}]);
    });

    //close spinner
    this.dialog.getDialogById('Tasks_spinnerRefresh')?.close();
  }

  private async checkBtn() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinnerButtonCheck',
      autoFocus: false,
      disableClose: true
    });

    this.admin = (await this.usertype.getShown()).admin;

    //close spinner
    this.dialog.getDialogById('Tasks_spinnerButtonCheck')?.close();
  }

  navigateToTask(id: number, type: string): void{

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinnerNavigate_tasks-task',
      autoFocus: false,
      disableClose: true
    });

    this.router.navigateByUrl('/app/tasks-task', {state: {id, type}})
  }

  navigateToAddTask(): void{

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinnerNavigate_tasks-add',
      autoFocus: false,
      disableClose: true
    });

    this.router.navigate(['/app/tasks-add'])
  }

  private delay(ms: number) {
    return new Promise( resolve => setTimeout(resolve, ms) );
  }
}
