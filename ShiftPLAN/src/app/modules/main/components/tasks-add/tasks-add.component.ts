/**
 * tasks-add.component.ts
 * 
 *  Main typescript class for the tasks-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-24 / Anne Naumann
 */
import { Component, OnInit, AfterViewChecked } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-tasks-add',
  templateUrl: './tasks-add.component.html',
  styleUrls: ['./tasks-add.component.scss']
})

export class TasksAddComponent implements OnInit, AfterViewChecked {

  viewLoaded = false

  title = ''
  saveBtn = ''
  labelDescription = ''
  checkBoxOnce = ''
  labelHint = ''

  constructor(private translate: TranslateService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.TasksAdd;
      this.labelDescription = translation.Tasks.Add.LabelDescription;
      this.checkBoxOnce = translation.Tasks.Add.CheckBoxOnce;
      this.labelHint = translation.Tasks.Add.LabelHint;
      this.saveBtn = translation.SaveButton;
    });
  }

  ngAfterViewChecked(): void{
    let btn = document.getElementById('saveTaskBtn')

    if(btn!.clientHeight != 0 && this.viewLoaded == false){
      let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
      let nameInput = document.getElementsByTagName('mat-form-field')[0].clientHeight
      let checkBox = document.getElementsByTagName('mat-checkbox')[0].clientHeight
      let buttonContainer = document.getElementById('buttonContainer')?.clientHeight
      var inputContainer = document.getElementById('inputContainer')
  
      let screenHeight = window.innerHeight-toolbar-nameInput-checkBox-buttonContainer!
      inputContainer!.style.height = screenHeight + 'px'

      this.viewLoaded = true
    }
  }

  showHint(isChecked: boolean): void{
    var hintDiv = document.getElementById('taskOnceHint')
    var inputDiv = document.getElementById('inputContainer')

    if(isChecked){
      hintDiv!.style.display = 'block'
      inputDiv!.style.display = 'none'
    }else{
      hintDiv!.style.display = 'none'
      inputDiv!.style.display = 'block'
    }
  }
}
