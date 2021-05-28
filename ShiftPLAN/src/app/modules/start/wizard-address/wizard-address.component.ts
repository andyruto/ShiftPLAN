/**
 * wizard-address.component.ts
 * 
 * Main typescript class for the wizard-address component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-28 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-wizard-address',
  templateUrl: './wizard-address.component.html',
  styleUrls: ['./wizard-address.component.scss']
})
export class WizardAddressComponent implements OnInit {
  
  constructor() { }
  ngOnInit(): void { }
  
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
