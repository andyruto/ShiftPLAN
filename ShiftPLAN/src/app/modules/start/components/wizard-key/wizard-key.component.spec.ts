/**
 * wizard-key.component.spec
 * 
 * Typescript class containing the tests for the 
 * wizard-key component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-26 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { WizardKeyComponent } from './wizard-key.component';

describe('WizardKeyComponent', () => {
  let component: WizardKeyComponent;
  let fixture: ComponentFixture<WizardKeyComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ WizardKeyComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(WizardKeyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
