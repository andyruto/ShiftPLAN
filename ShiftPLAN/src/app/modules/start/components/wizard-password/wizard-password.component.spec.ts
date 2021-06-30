/**
 * wizard-password.component.spec
 * 
 * Typescript class containing the tests for the 
 * wizard-password component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-26 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { WizardPasswordComponent } from './wizard-password.component';

describe('WizardPasswordComponent', () => {
  let component: WizardPasswordComponent;
  let fixture: ComponentFixture<WizardPasswordComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WizardPasswordComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WizardPasswordComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
