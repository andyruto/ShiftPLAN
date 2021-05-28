/**
 * wizard-key.component.ts
 * 
 * Main typescript class for the wizard-key component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-28 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-wizard-key',
  templateUrl: './wizard-key.component.html',
  styleUrls: ['./wizard-key.component.scss']
})
export class WizardKeyComponent implements OnInit {

  constructor() { }
  ngOnInit(): void {
  }

  checkInput(userInput: string): void{
    let warning = document.getElementById('warning')
    var inputValid: Boolean = false

    //check Input
    console.log(userInput)

    if(inputValid){
      //navigate to next component
    }else{
      warning!.style.visibility = 'visible'
    }
  }
}
